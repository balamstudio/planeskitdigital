<?php

/**
 * Class to manage the connection to the Database
 * @package Functions
 */

class db
{
    /**
     * Link ID for every connection
     * @var int
     */

    var $link_id = 0;

    /**
     * Query ID for every query
     * @var int
     */

    var $query_id = 0;

    /**
     * Errordescription, if an error occures
     * @var string
     */

    var $errdesc = '';

    /**
     * Errornumber, if an error occures
     * @var int
     */

    var $errno = 0;

    /**
     * Servername
     * @var string
     */

    var $server = '';

    /**
     * Username
     * @var string
     */

    var $user = '';

    /**
     * Password
     * @var string
     */

    var $password = '';

    /**
     * Database
     * @var string
     */

    var $database = '';

    /**
     * Class constructor. Connects to Databaseserver and selects Database
     *
     * @param string Servername
     * @param string Username
     * @param string Password
     * @param string Database
     */

    function __construct($server, $user, $password, $database = '')
    {
        // check for mysql extension

        if(!extension_loaded('mysqli'))
        {
            $this->showerror('You should install the PHP MySQL extension!', false);
        }

        $this->server = $server;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;

        /*
               echo '<br />server ='.$this->server;
               echo '<br />user ='.$this->user;
               echo '<br />password ='.$this->password;
               echo '<br />database ='.$this->database;
               echo '<br />';
              */

        $this->link_id = @mysqli_connect($this->server, $this->user, $this->password);
        mysqli_set_charset($this->link_id,'utf8');
        //mysql_query("SET NAMES 'utf8'");

        if(!$this->link_id)
        {
            $this->showerror('Establishing connection failed, exiting');
        }

        if($this->database != '')
        {
            if(!@mysqli_select_db($this->link_id, $this->database))
            {
                $this->showerror('Trying to use database ' . $this->database . ' failed, exiting');
            }
        }
    }

    /**
     * Closes connection to Databaseserver
     */

    function close()
    {
        return @mysqli_close($this->link_id);
    }

    /**
     * Escapes user input to be used in mysql queries
     *
     * @param string $input
     * @return string escaped string
     */

    function escape($input)
    {
        $input = stripslashes($input);

        if(is_int($input))
        {
            return (int)$input;
        }
        elseif(is_float($input))
        {
            return (float)$input;
        }
        else
        {
            return mysqli_real_escape_string($this->link_id,$input);
        }

    }

    /**
     * Query the Database
     *
     * @param string Querystring
     * @param bool Unbuffered query?
     * @return string RessourceId
     */

    function query($query_str,$errormsg='<br />Invalid SQL: ')
    {
        global $numbqueries;


        $this->query_id = mysqli_query($this->link_id,$query_str);

        if(!$this->query_id)
        {
            $this->showerror($errormsg . $query_str);
        }

        $numbqueries++;

        //pe($query_str.' '.$numbqueries.'<br />');

        //$this->query_id =  $mysqli->store_result();

        return $this->query_id;
    }

    /**
     * Fetches Row from Query and returns it as array
     *
     * @param string RessourceId
     * @param string Datatype, num or assoc
     * @return array The row
     */

    function fetch_array($query, $datatype = 'assoc')
    {

        if ($datatype == 'assoc') {
            $record = mysqli_fetch_array($this->query_id, MYSQLI_ASSOC);
        } elseif ($datatype == 'num') {
            $record = mysqli_fetch_array($this->query_id, MYSQLI_NUM);
        } else {
            $record = mysqli_fetch_array($this->query_id, MYSQLI_BOTH);
        }

        //pp($record);

        return $record;


    }

    /**
     * Query Database and fetche the first row from Query and returns it as array
     *
     * @param string Querystring
     * @param string Datatype, num or assoc
     * @return array The first row
     */

    function query_first($query_string, $datatype = 'assoc')
    {
        $this->query($query_string);
        return $this->fetch_array($this->query_id, $datatype);
    }

    /**
     * Returns how many rows have been selected
     *
     * @param string RessourceId
     * @return int Number of rows
     */

    function num_rows($query_id = null)
    {
        if(!is_null($query_id))
        {
            $this->query_id = $query_id;
        }

        return mysqli_num_rows($this->query_id);
    }

    /**
     * Returns the auto_incremental-Value of the inserted row
     *
     * @return int auto_incremental-Value
     */

    function insert_id()
    {
        return mysqli_insert_id($this->link_id);
    }

    /**
     * Returns the number of rows affected by last query
     *
     * @return int affected rows
     */

    function affected_rows()
    {
        return mysqli_affected_rows($this->link_id);
    }

    /**
     * Returns errordescription and errornumber if an error occured.
     *
     * @return int Errornumber
     */

    function geterrdescno()
    {
        if($this->link_id != 0)
        {
            $this->errdesc = mysqli_error($this->link_id);
            $this->errno = mysqli_errno($this->link_id);
        }
        else
        {
            // Maybe we don't have any linkid so let's try to catch at least anything

            $this->errdesc = mysqli_error();
            $this->errno = mysqli_errno();
        }

        return $this->errno;
    }

    /**
     * Dies with an errormessage
     *
     * @param string Errormessage
     */

    function showerror($errormsg, $mysqlActive = true)
    {
        global $filename;

        if($mysqlActive)
        {
            $this->geterrdescno();
            $errormsg.= "\n";
            $errormsg.= 'mysql error number: ' . $this->errno . "\n";
            $errormsg.= 'mysql error desc: ' . $this->errdesc . "\n";
        }

        $errormsg.= 'Time/date: ' . date('d/m/Y h:i A') . "\n";
        $errormsg.= 'Script: ' . htmlspecialchars($_SERVER['REQUEST_URI']) . "\n";
        $errormsg.= 'Referer: ' . htmlspecialchars(getenv('HTTP_REFERER')) . "\n";
        die(nl2br($errormsg));
    }
}

?>
