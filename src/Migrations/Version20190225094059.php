<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190225094059 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE liste_course ADD produits_id INT NOT NULL');
        $this->addSql('ALTER TABLE liste_course ADD CONSTRAINT FK_27EF1A82CD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_27EF1A82CD11A2CF ON liste_course (produits_id)');
        $this->addSql('ALTER TABLE produit DROP liste_course_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE liste_course DROP FOREIGN KEY FK_27EF1A82CD11A2CF');
        $this->addSql('DROP INDEX IDX_27EF1A82CD11A2CF ON liste_course');
        $this->addSql('ALTER TABLE liste_course DROP produits_id');
        $this->addSql('ALTER TABLE produit ADD liste_course_id INT NOT NULL');
    }
}
