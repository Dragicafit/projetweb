<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519175636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, temps INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_FDCA8C9C60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, cour_id INT NOT NULL, exo LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', consigne VARCHAR(255) NOT NULL, INDEX IDX_E418C74DB7942F03 (cour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice_ligne (exercice_id INT NOT NULL, ligne_id INT NOT NULL, INDEX IDX_E7D1D6A89D40298 (exercice_id), INDEX IDX_E7D1D6A5A438E76 (ligne_id), PRIMARY KEY(exercice_id, ligne_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_57F0DB833B8BA7C7 (text), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solution (id INT AUTO_INCREMENT NOT NULL, exercice_id INT NOT NULL, INDEX IDX_9F3329DB89D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tab (id INT AUTO_INCREMENT NOT NULL, ligne_id INT NOT NULL, solution_id INT NOT NULL, nb_tab INT NOT NULL, INDEX IDX_73E3430C5A438E76 (ligne_id), INDEX IDX_73E3430C1C0BE183 (solution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, prof TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_cours (user_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_1F0877C4A76ED395 (user_id), INDEX IDX_1F0877C47ECF78B0 (cours_id), PRIMARY KEY(user_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DB7942F03 FOREIGN KEY (cour_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE exercice_ligne ADD CONSTRAINT FK_E7D1D6A89D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice_ligne ADD CONSTRAINT FK_E7D1D6A5A438E76 FOREIGN KEY (ligne_id) REFERENCES ligne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DB89D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE tab ADD CONSTRAINT FK_73E3430C5A438E76 FOREIGN KEY (ligne_id) REFERENCES ligne (id)');
        $this->addSql('ALTER TABLE tab ADD CONSTRAINT FK_73E3430C1C0BE183 FOREIGN KEY (solution_id) REFERENCES solution (id)');
        $this->addSql('ALTER TABLE user_cours ADD CONSTRAINT FK_1F0877C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cours ADD CONSTRAINT FK_1F0877C47ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DB7942F03');
        $this->addSql('ALTER TABLE user_cours DROP FOREIGN KEY FK_1F0877C47ECF78B0');
        $this->addSql('ALTER TABLE exercice_ligne DROP FOREIGN KEY FK_E7D1D6A89D40298');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DB89D40298');
        $this->addSql('ALTER TABLE exercice_ligne DROP FOREIGN KEY FK_E7D1D6A5A438E76');
        $this->addSql('ALTER TABLE tab DROP FOREIGN KEY FK_73E3430C5A438E76');
        $this->addSql('ALTER TABLE tab DROP FOREIGN KEY FK_73E3430C1C0BE183');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C60BB6FE6');
        $this->addSql('ALTER TABLE user_cours DROP FOREIGN KEY FK_1F0877C4A76ED395');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE exercice_ligne');
        $this->addSql('DROP TABLE ligne');
        $this->addSql('DROP TABLE solution');
        $this->addSql('DROP TABLE tab');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_cours');
    }
}
