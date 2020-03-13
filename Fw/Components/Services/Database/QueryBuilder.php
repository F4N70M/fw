<?php


namespace Fw\Components\Services\Database;


use Exception;
use InvalidArgumentException;
use PDO;
use PDOException;
use PDOStatement;

class QueryBuilder
{
	private $_link;

	private $sql;
	private $query = [];
	private $bind = [];
	private $statement;


	public function __construct(Connection $connection)
	{
		$this->_link = $connection->get();
	}


	/**
	 * @param string $sql
	 * @param array $params
	 * @return $this
	 */
	public function query(string $sql, array $params = [])
	{
		$db = clone $this;
		$db->sql = $sql;
		$db->bind = $params;

		return $db;
	}


	/**
	 * @param null $columns
	 * @return $this
	 * @throws Exception
	 */
	public function select($columns=null)
	{
		$db = clone $this;
		$db->bind = [];
		$db->sql = '';
		$db->query = [
			'action'    => 'SELECT',
			'columns'   => $this->columns($columns),
			'from'      => null,
			'where'     => null,
			'orderBy'   => null,
			'limit'     => null
		];
//		$db->sql = /** @lang text */
//			"SELECT {$this->columns($columns)}";

		return $db;
	}


	/**
	 * @return $this
	 */
	public function insert()
	{
		$db = clone $this;
		$db->bind = [];
		$db->sql = '';
		$db->query = [
			'action'    => 'INSERT',
			'into'      => null,
			'values'    => null
		];
//		$db->sql = /** @lang text */
//			"INSERT";

		return $db;
	}


	/**
	 * @param string $table
	 * @return $this
	 */
	public function update(string $table)
	{
		$db = clone $this;
		$db->bind = [];
		$db->sql = '';
		$db->query = [
			'action'    => 'UPDATE',
			'table'     => $table,
			'set'       => null,
			'where'     => null
		];
//		$db->sql = "UPDATE $table";

		return $db;
	}


	/**
	 * @return $this
	 */
	public function delete()
	{
		$db = clone $this;
		$db->bind = [];
		$db->sql = '';
		$db->query = [
			'action'    => 'DELETE',
			'from'      => null,
			'where'     => null
		];

		return $db;
	}


	/**
	 * SHOW DATABASES; - список баз данных
	 * SHOW TABLES [FROM db_name]; -  список таблиц в базе
	 * SHOW COLUMNS FROM таблица [FROM db_name]; - список столбцов в таблице
	 * SHOW CREATE TABLE table_name; - показать структуру таблицы в формате "CREATE TABLE"
	 * SHOW INDEX FROM tbl_name; - список индексов
	 * SHOW GRANTS FOR user [FROM db_name]; - привилегии для пользователя.
	 * SHOW VARIABLES; - значения системных переменных
	 * SHOW [FULL] PROCESSLIST; - статистика по mysqld процессам
	 * SHOW STATUS; - общая статистика
	 * SHOW TABLE STATUS [FROM db_name]; - статистика по всем таблицам в базе
	 */
	/**
	 * @param $type
	 * @return QueryBuilder
	 */
	public function show($type, $from = null)
	{
		$showType = strtoupper($type);
		if (!in_array($showType,['TABLES','COLUMNS','INDEX','VARIABLES']))
			throw new InvalidArgumentException("Invalid argument \"$type\" must be TABLES|COLUMNS|INDEX|VARIABLES");


		$db = clone $this;
		$db->bind = [];
		$db->sql = '';
		$db->query = [
			'action'    => 'SHOW',
			'show'      => $showType,
			'from'      => $from
		];
//		$db->sql = "SHOW {$showType}";

		return $db;
	}


	/**
	 * @param string $table
	 * @return $this
	 * @throws Exception
	 */
	public function from(string $table)
	{
		if (!array_key_exists('from',$this->query))
			throw new Exception("method \"from\" cannot be used with \"{$this->query['action']}\"");

		$this->query['from'] = $table;
//		$this->sql .= /** @lang text */
//			" FROM $table";

		return $this;
	}


	/**
	 * @param string $table
	 * @return $this
	 * @throws Exception
	 */
	public function into(string $table)
	{
		if (!array_key_exists('into',$this->query))
			throw new Exception("method \"into\" cannot be used with \"{$this->query['action']}\"");

		$this->query['into'] = $table;
//		$this->sql .= " INTO $table";

		return $this;
	}


	/**
	 * @param null $columns
	 * @return string|null
	 * @throws Exception
	 */
	private function columns($columns=null)
	{
		if (empty($columns)) {
			$result = "*";
		}
		elseif (is_array($columns))
		{
			$result = implode(', ', $columns);
		}
		elseif (is_string($columns))
		{
			//TODO: Написать проверку по шаблону
			$result = $columns;
		}
		else
		{
			throw new Exception('Request Error: Invalid query result column format');
		}

		return $result;
	}


	/**
	 * @param array $values
	 * @return $this
	 * @throws Exception
	 */
	public function set(array $values=[])
	{
		if (!array_key_exists('set',$this->query))
			throw new Exception("method \"set\" cannot be used with \"{$this->query['action']}\"");

		if (!empty($values)) {
//			$this->sql .= " SET";

			$bindValues = [];
			foreach ($values as $key => $value)
			{
//				$bindValues[$key] = " {$key} = :{$key}";
				$bindValues[] = "{$key} = :{$key}";
				$this->bind[":{$key}"] = $value;
			}
//			$this->sql .= implode(",", $bindValues);
			$this->query['set'] = implode(", ", $bindValues);
		}
		else
		{
			throw new Exception('Request failed: no values to update');
		}

		return $this;
	}

	/**
	 * @param array $values
	 * @return $this
	 * @throws Exception
	 */
	public function values(array $values=[])
	{
		if (!array_key_exists('values',$this->query))
			throw new Exception("method \"values\" cannot be used with \"{$this->query['action']}\"");

		if (!empty($values)) {
//			$this->sql .= " (";
//			$this->sql .= implode(", ", array_keys($values));
//			$this->sql .= ") VALUES (";
			$result = "(";
			$result .= implode(", ", array_keys($values));
			$result .= ") VALUES (";
			$bindValues = [];
			foreach ($values as $key => $value)
			{
				$bindValues[$key] = ":$key";
				$this->bind[":$key"] = $value;
			}
			$result .= implode(", ", $bindValues);
			$result .= ")";

			$this->query['values'] = $result;
		}
		else
		{
			throw new Exception('Request failed: no values to update');
		}

		return $this;
	}

	/**
	 * @param $orderBy
	 * @return $this
	 * @throws Exception
	 */
	public function orderBy($orderBy)
	{
		if (!array_key_exists('orderBy',$this->query))
			throw new Exception("method \"orderBy\" cannot be used with \"{$this->query['action']}\"");

		if (!empty($orderBy))
		{
//			$this->sql .= " ORDER BY";

			if ( is_array($orderBy) )
			{
				$result = [];
				foreach ($orderBy as $key => $value)
				{
					if (is_numeric($key))
					{
//						$this->sql .= " $value ASC";
						$result[] = "$value ASC";
					}
					else
					{
//						$this->sql .= " $key " . ((!$value || strtoupper($value) == "DESC") ? "DESC" : "ASC");
						$result[] = "$key " . (!$value || strtoupper($value) == "DESC") ? "DESC" : "ASC";
					}
				}
				$result = implode(', ', $result);
			}
			//TODO: Написать проверку с ASC и DESC
			elseif(preg_match('/^[A-z0-9\-_, ]+$/', $orderBy))
			{
//				$this->sql .= " $orderBy";
				$result = $orderBy;
			}
			else
			{
				throw new Exception('Invalid argument type');
			}
			$this->query['orderBy'] = $result;
		}

		return $this;
	}

	/**
	 * @param int|null $count
	 * @param int|null $offset
	 * @return $this
	 * @throws Exception
	 */
	public function limit(int $count=null, int $offset=null)
	{
		if (!array_key_exists('limit',$this->query))
			throw new Exception("method \"limit\" cannot be used with \"{$this->query['action']}\"");

		if ($count !== null)
//			$this->sql .= " LIMIT $l1" . ($l2 !== null ? ", $l2" : "");
			$this->query['limit'] = $count . ($offset !== null ? " OFFSET {$offset}" : "");
		return $this;
	}


	/**
	 * @param string $column
	 * @return array
	 * @throws Exception
	 */
	public function resultBy(string $column)
	{
		$resultBy = [];

		$result = $this->result();

		foreach ($result as $item)
		{
			if (isset($item[$column]))
			{
				$resultBy[$item[$column]] = $item;
			}
			else
			{
				break;
			}
		}
		return $resultBy;
	}


	/**
	 * @param bool $single
	 * @return array
	 * @throws Exception
	 */
	public function result(bool $single = false)
	{
		$query = [];
		foreach ($this->query as $key => $value)
		{
			if (empty($value)) continue;
			if ($key == 'into') $value = "INTO {$value}";
			if ($key == 'from') $value = "FROM {$value}";
			if ($key == 'where') $value = "WHERE {$value}";
			if ($key == 'set') $value = "SET {$value}";
			if ($key == 'orderBy') $value = "ORDER BY {$value}";
			if ($key == 'limit') $value = "LIMIT {$value}";

			$query[] = $value;
		}
		$this->sql = implode(' ', $query);

//		debug($this->bind, $this->sql);

		$this->statement = $this->prepare($this->sql, $this->bind);
		$this->statement = $this->execute($this->statement);

		if($this->statement)
		{
//			debug('statement', $this->statement);
			if ($this->query['action'] == 'INSERT')
			{
				$result = $this->_link->lastInsertId();
			}
			elseif (in_array($this->query['action'], ['SELECT','SHOW']))
			{
				if($single)
				{
					$result = $this->statement->fetch(PDO::FETCH_ASSOC);
				}
				else
				{
					$result = $this->statement->fetchAll(PDO::FETCH_ASSOC);
				}
			}
			else
			{
				$result = true;
			}
		}
		else
		{
			$result = false;
		}

//		return $result;
		return $result;
	}


	/**
	 * @param string|null $key
	 * @return array|bool|string
	 * @throws Exception
	 */
//	public function all(string $key=null)
//	{
//		$result = $this->do();
//
//		if ( $result )
//		{
//			$result = $this->statement->fetchAll(PDO::FETCH_ASSOC);
//		}
//		if(!empty($key) && isset($result[0][$key]))
//		{
//			$tmp = [];
//			foreach ($result as $item)
//			{
//				$tmp[$item[$key]] = $item;
//			}
//			$result = $tmp;
//		}
//
//		return $result;
//	}


	/**
	 * @return array|false
	 * @throws Exception
	 */
//	public function one()
//	{
//		$result = $this->do();
//		if ($result)
//			return $this->statement->fetch(PDO::FETCH_ASSOC);
//
//		return false;
//	}


	/**
	 * @return bool|string
	 * @throws Exception
	 */
//	public function do()
//	{
//		$this->statement = $this->prepare($this->sql, $this->bind);
//
//		$this->statement = $this->execute($this->statement);
//
//
//		if($this->statement)
//		{
//			if (preg_match('#^insert#is', $this->sql))
//			{
//				$result = $this->_link->lastInsertId();
//			}
//			else
//			{
//				$result = true;
//			}
//		}
//		else
//		{
//			$result = false;
//		}
//
//		return $result;
//	}


	/**
	 * @param null $where
	 * @return $this
	 */
	public function where($where=null)
	{
		$whereString = $this->recursiveWhere($where);
		//Common::print($whereString);
		if ( !empty($whereString) )
		{
//			$this->sql .= " WHERE $whereString";
			$this->query['where'] = $whereString;
		}

//		Common::print(
//			$this->sql,
//			$this->bind
//		);

		return $this;
	}











	/**
	 * @param $sql
	 * @param array $bind
	 * @return bool|PDOStatement
	 */
	private function prepare($sql, array $bind)
	{
		try
		{
			$statement = $this->_link->prepare($sql);
			if (!empty($bind))
			{
				foreach ($bind as $key => $value)
				{
					$statement->bindValue($key, $value);
				}
			}
		}
		catch (PDOException $e)
		{
			echo "Request failed: ";
			echo $e->getMessage();
			$statement = null;
		}

		return $statement;
	}

	/**
	 * @param PDOStatement $statement
	 * @return PDOStatement
	 * @throws Exception
	 */
	private function execute(PDOStatement $statement)
	{
		try
		{
			$statement->execute();
		}
		catch (PDOException $e)
		{
			echo "Request error: ";
			echo $e->getMessage();

			$statement = false;
		}
		return $statement;
	}

	/**
	 * @param   $where
	 * @return  string
	 *
	 * @example ['id'=>5]
	 * @result  WHERE id = 5
	 *
	 * @example ['id'=>['>=', 7]]
	 * @result  WHERE id >= 7
	 *
	 * @example ['id'=>['<', 4], ['or', 'id'=>['>', 6]]]
	 * @result  WHERE id < 4 || id > 6
	 *
	 * @example ['id'=>['like', '%_fw%']]
	 * @result  WHERE id LIKE '%_fw%'
	 *
	 * @example ['date'=>['between', ['2019-03-03', '2019-03-05']]]
	 * @result  WHERE date BETWEEN '2019-03-03' AND '2019-03-05'
	 *
	 * @example ['date'=>['in', ['2019-03-04', '2019-03-11']]]
	 * @result  WHERE date IN ('2019-03-04', '2019-03-11')
	 */
	private function recursiveWhere($where)
	{
		$result = '';

		if (!empty($where))
		{

			if (is_array($where))
			{
				$result = '';
				foreach ($where as $key => $item) {
					if (is_numeric($key) && is_array($item))
					{
						if ($this->isset_glue($item))
						{
							$glue = $this->get_glue($item);
							foreach ($item as $ikey => $value)
							{
								if ( is_numeric($ikey) )
								{
									$tmp = $this->recursiveWhere($value);
									if (!empty($tmp))
										$result .= " $glue ($tmp)";
								}
								else
								{
									if (!is_array($value))
										$value = ['=',$value];
									$tmp = $this->parseValueWhere($value);
									if (!empty($tmp))
										$result .= " $glue $ikey $tmp";
								}
							}
						}
						else
						{
							$tmp = $this->recursiveWhere($item);

							if (!empty($tmp))
							{
								if ( !empty($result) ) $result .= " && ";
								$result .= '(' . $tmp . ')';
							}
						}

					}
					elseif(is_string($key) && !empty($key))
					{
						if(is_scalar($item))
						{
							if ( !empty($result) ) $result .= " && ";
							$bindName = ":where" . count($this->bind);
							$this->bind[$bindName] = $item;
							$result .= "$key = $bindName";
						}
						elseif (is_null($item))
						{
							if ( !empty($result) ) $result .= " && ";
							$result .= "$key IS NULL";
						}
						elseif (count($item) == 2 && isset($item[0]) && is_string($item[0]))
						{
							$tmp = $this->parseValueWhere($item);
							if (!empty($tmp))
							{
								if ( !empty($result) ) $result .= " && ";
								$result .= "$key $tmp";
							}
						}
					}
					elseif (is_numeric($key) && is_string($item) && !empty($item))
					{
						if ( !empty($result) ) $result .= " && ";
						$result .= $item;
					}
				}
			}
			elseif(
				is_string($where)
				&&
				!in_array(strtoupper($where),['AND','OR','&&','||'])
			)
			{
				$result = $where;
			}
			else
			{
				#
			}
		}

		return $result;
	}

	/**
	 * @param array $item
	 * @return bool
	 */
	private function isset_glue(array $item)
	{
		return (count($item) == 2 && isset($item[0]) && is_string($item[0]) && in_array(strtoupper($item[0]), ['AND','OR','&&','||']));
	}

	/**
	 * @param array $item
	 * @return string
	 */
	private function get_glue(array $item)
	{
		return (in_array(strtoupper(array_shift($item)), ['OR','||']) ? '||' : '&&');
	}

	/**
	 * @param array $array
	 * @return string
	 */
	private function parseValueWhere($array)
	{
		$result = '';
		$operator = strtoupper(array_shift($array));
		foreach ($array as $value)
		{
			if (in_array($operator, ['=','>','<','>=','<=','<>','LIKE']) && is_scalar($value))
			{
				$bindName = ":where" . count($this->bind);
				$this->bind[$bindName] = $value;
				$result .= "$operator $bindName";
			}
			elseif ($operator == 'BETWEEN' && is_array($value) && count($value) == 2 && is_scalar($value[0]) && !empty($value[0]) && is_scalar($value[1]) && !empty($value[1]))
			{
				$result = "$operator ";

				$bindName = ":where" . count($this->bind);
				$this->bind[$bindName] = $value[0];
				$result .= $bindName;

				$result .= " AND ";

				$bindName = ":where" . count($this->bind);
				$this->bind[$bindName] = $value[1];
				$result .= $bindName;
			}
			elseif ($operator == 'IN' && is_array($value) && !empty($value) )
			{
				$in = [];
				foreach ($value as $inValue)
				{
					if ( is_scalar($inValue) || is_null($inValue) )
					{
						$in[] = $inValue;
					}
					else
					{
						$in = [];
						break;
					}
				}
				if (!empty($in))
				{
					foreach ($in as $key => $item)
					{
						$bindName = ":where" . count($this->bind);
						$this->bind[$bindName] = $item;
						$in[$key] = $bindName;
					}
					$result = "$operator (" . implode(',', $in) . ")";
				}
			}
			break;
		}

		return $result;
	}
}