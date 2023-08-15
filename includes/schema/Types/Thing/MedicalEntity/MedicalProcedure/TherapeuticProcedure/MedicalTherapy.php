<?php

// MedicalTherapy

	/*
	 * Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
	 * 
	 * 
	 */

	function uamswp_fad_schema_medicaltherapy(
		
	) {
		
	}

	// OccupationalTherapy
	include_once __DIR__ . '/MedicalTherapy/OccupationalTherapy.php';

	// PalliativeProcedure
	include_once __DIR__ . '/MedicalTherapy/PalliativeProcedure.php';

	// PhysicalTherapy
	include_once __DIR__ . '/MedicalTherapy/PhysicalTherapy.php';

	// RadiationTherapy
	include_once __DIR__ . '/MedicalTherapy/RadiationTherapy.php';

	// RespiratoryTherapy
	include_once __DIR__ . '/MedicalTherapy/RespiratoryTherapy.php';