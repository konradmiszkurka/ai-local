<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407190754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, fixture_id INT NOT NULL, country_name VARCHAR(100) NOT NULL, league_id INT NOT NULL, league_name VARCHAR(100) NOT NULL, season_year VARCHAR(9) NOT NULL, season_stage VARCHAR(50) NOT NULL, round VARCHAR(10) DEFAULT NULL, date DATETIME NOT NULL, referee VARCHAR(100) DEFAULT NULL, home_team_id INT NOT NULL, home_team_name VARCHAR(100) NOT NULL, home_team_coach VARCHAR(100) DEFAULT NULL, away_team_id INT NOT NULL, away_team_name VARCHAR(100) NOT NULL, away_team_coach VARCHAR(100) DEFAULT NULL, goals_home INT NOT NULL, goals_away INT NOT NULL, goals_home_ht INT NOT NULL, goals_away_ht INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE games
        SQL);
    }
}
