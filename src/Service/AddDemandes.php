<?php

namespace App\Service;

use App\Repository\StatutRepository;
use App\Repository\TypeMachineRepository;

class AddDemandes
{
	public TypeMachineRepository $typeMachineRepository;
	public StatutRepository $statutRepository;

	public function __construct(
		TypeMachineRepository $typeMachineRepository,
	 	StatutRepository $statutRepository
	)
	{
		$this->typeMachineRepository = $typeMachineRepository;
		$this->statutRepository = $statutRepository;
	}

	public function addTypeMachines(
		$machinesTab,$demandeJson)
	{
		foreach ($machinesTab as $key=>$idMachine){
			$demandeJson->addMachine($this->typeMachineRepository->findOneBy(
				['id'=>$idMachine]));
		}
	}

	public function addStatut(
		$idStatut)
	{
		return $this->statutRepository->findOneBy(
			['id'=>$idStatut]);
	}


}