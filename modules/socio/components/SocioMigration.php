<?php


class SocioMigration extends ModuleMigration
{
	
	public function getComponentName(){
		return 'socio';
	}
	
	public function hasDb()
	{
		return true;
	}
	
	public function installDb(){

		
		$this->getDbConnection()->createCommand()->createTable("{{socio}}", array(
		
				'id_socio' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
				'type' => 'INT NOT NULL',
				'sex' => 'CHAR(1) NOT NULL',
				'firstname' => 'VARCHAR(50) NOT NULL',
				'lastname' => 'VARCHAR(50) NULL',
				'id_type' => 'INT NOT NULL',
				'id' => 'VARCHAR(32) NOT NULL',
				'birthdate' => 'DATE NOT NULL',
				'address_street' => 'VARCHAR(255) NOT NULL',
				'address_number' => 'VARCHAR(16)',
				'address_other' => 'VARCHAR(255) NOT NULL',
				'address_country' => 'VARCHAR(8) NOT NULL',
				'address_province' => 'INT NULL',
				'address_city' => 'VARCHAR(50) NOT NULL',
				'address_postal_code' => 'VARCHAR(16) NOT NULL',
				'phone' => 'VARCHAR(32) NOT NULL',
				'mobile' => 'VARCHAR(32) NOT NULL',
				'email' => 'VARCHAR(255) NOT NULL',
				'known' => 'INT NOT NULL',
				'known_other' => 'VARCHAR(32) NULL',
				'comments' => 'TEXT NULL',
				
				'donation' => 'DECIMAL(10,2) NOT NULL',
				'donation_periodicity' => 'INT NOT NULL',
				'donation_index' => 'INT NOT NULL',
				
				'bank_name' => 'VARCHAR(50) NOT NULL',
				'account_number' => 'VARCHAR(50) NOT NULL',
				'account_owner' => 'VARCHAR(255) NOT NULL',
				
				
				'company_name' => 'VARCHAR(50) NULL',
				'company_contact' => 'VARCHAR(50) NULL',
				'company_id' => 'VARCHAR(32) NOT NULL',
				

				'PRIMARY KEY (`id_socio`)',
		
		
		));
		
		
		return true;
	}
	
	public function updateDb()
	{
		
		switch($this->getCurrentVersion())
		{
			case '1.0':
				
				$this->getDbConnection()->createCommand()->dropColumn("{{socio}}", 'company_sex');
				$this->getDbConnection()->createCommand()->dropColumn("{{socio}}", 'company_firstname');
				$this->getDbConnection()->createCommand()->dropColumn("{{socio}}", 'company_lastname');
				$this->getDbConnection()->createCommand()->dropColumn("{{socio}}", 'company_id_type');
				
				$this->getDbConnection()->createCommand()->addColumn("{{socio}}", 'company_name', 'VARCHAR(50) NULL');
				$this->getDbConnection()->createCommand()->addColumn("{{socio}}", 'company_contact', 'VARCHAR(50) NULL');
				
			
			case '1.1':
			
				$this->getDbConnection()->createCommand()->addColumn("{{socio}}", 'donation_index', 'INT NOT NULL');
			
			case '1.2':
					
				$this->getDbConnection()->createCommand()->addColumn("{{socio}}", 'bank_name', 'VARCHAR(50) NOT NULL');
			

			
		}
	
		
		return true;
		
	}
	
}