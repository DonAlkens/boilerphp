<?php 

namespace App\Core\Database\Console\Support;

use App\Core\Database\Migration\Migration;

class MigrationReflection extends Migration {

    public $table = "migrations";

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    public function init() {

        $this->query("CREATE TABLE IF NOT EXISTS migrations(
            `id` INT(9) NOT NULL AUTO_INCREMENT,
            `migration` VARCHAR(255) DEFAULT NULL,
            `version` INT(9) DEFAULT NULL,
            `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(`id`)
            )
        ");

    }

    public function checkMigration($migration) {

        $checker = $this->query("SELECT migration FROM migrations WHERE migration = '$migration'");
        if($checker->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function clearTables() {
        $this->run("TRUNCATE `migrations`");
    }

}