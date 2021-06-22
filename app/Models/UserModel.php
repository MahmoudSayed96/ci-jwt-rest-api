<?php

namespace App\Models;

use App\Libraries\Hash;
use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'users';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'name',
		'email',
		'password'
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = ['beforeInsert'];
	protected $afterInsert          = [];
	protected $beforeUpdate         = ['beforeUpdate'];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	// --------------------------------------------------------.

	/**
	 * Make password value hashed if exists before insert user data.
	 * 
	 * @param array $data request data.
	 * @return array $data.
	 */
	protected function beforeInsert(array $data): array
	{
		return $this->getUpdatedDataWithHashedPassword($data);
	}

	/**
	 * Make password value hashed if exists before update user data.
	 * 
	 * @param array $data request data.
	 * @return array $data.
	 */
	protected function beforeUpdate(array $data): array
	{
		return $this->getUpdatedDataWithHashedPassword($data);
	}

	/**
	 * Make hashed password
	 */
	private function getUpdatedDataWithHashedPassword(array $data): array
	{
		if (isset($data['data']['password'])) {
			$plaintextPassword = $data['data']['password'];
			$data['data']['password'] = Hash::make($plaintextPassword);
		}
		return $data;
	}

	// --------------------------------------------------------.

	/**
	 * Get user based on his email.
	 * 
	 * @param string $email. User email address.
	 * @return mixed $user.
	 */
	public function findUserByEmailAddress(string $emailAddress)
	{
		$user = $this
			->asArray()
			->where(['email' => $emailAddress])
			->first();

		if (!$user)
			throw new Exception('User does not exist for specified email address');

		return $user;
	}
}