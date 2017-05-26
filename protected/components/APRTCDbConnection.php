<?php

class APRTCDbConnection extends CDbConnection
{
    public static $connectionContainer = array();

    /**
     * Creates the PDO instance.
     *
     * When some functionalities are missing in the pdo driver, we may use
     * an adapter class to provides them.
     *
     * @return PDO the pdo instance
     * @since 1.0.4
     */
    protected function createPdoInstance()
    {
        static::$connectionContainer[$this->connectionString] = parent::createPdoInstance();
        return static::$connectionContainer[$this->connectionString];
    }

    /**
     * Closes the currently active DB connection.
     *
     * It does nothing if the connection is already closed.
     *
     * @return bool.
     */
    public static function closeAllDB()
    {
        Yii::trace('closeAllDB connection', 'system.db.CDbConnection');
        foreach (static::$connectionContainer as $k => $pdo) {
            $pdo = null;
            unset(static::$connectionContainer[$k]);
        }
        return true;
    }

    public function createCommand($query = null)
    {
        //$this->close();
        $this->setActive(true);
        return new APRTCDbCommand($this, $query);
    }
}


class APRTCDbCommand extends CDbCommand
{

	public function splitCompany($prefix = null)
	{
//        echo $this->getText();
		return $this;
	}
}