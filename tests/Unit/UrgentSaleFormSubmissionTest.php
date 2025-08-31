<?php

namespace Tests\Unit;

use Tests\TestCase;

class UrgentSaleFormSubmissionTest extends TestCase
{
    /** @test */
    public function it_verifies_location_data_is_populated_in_hidden_fields()
    {
        // This test verifies that the JavaScript code in step4.blade.php
        // properly populates the hidden location fields from session data
        
        // Read the step4 template
        $step4Path = resource_path('views/prestataire/urgent-sales/steps/step4.blade.php');
        $this->assertFileExists($step4Path);
        
        $content = file_get_contents($step4Path);
        
        // Check that hidden fields exist for location data
        $this->assertStringContainsString('<input type="hidden" name="location" id="hidden_location"', $content);
        $this->assertStringContainsString('<input type="hidden" name="latitude" id="hidden_latitude"', $content);
        $this->assertStringContainsString('<input type="hidden" name="longitude" id="hidden_longitude"', $content);
        
        // Check that the updateReviewStep function exists and handles location data
        $this->assertStringContainsString('function updateReviewStep()', $content);
        
        // Check that the function populates location data from session
        $this->assertStringContainsString('sessionData.location', $content);
        $this->assertStringContainsString('sessionData.latitude', $content);
        $this->assertStringContainsString('sessionData.longitude', $content);
        
        // Check that the function sets the hidden field values
        $this->assertStringContainsString('document.getElementById(\'hidden_location\').value', $content);
        $this->assertStringContainsString('document.getElementById(\'hidden_latitude\').value', $content);
        $this->assertStringContainsString('document.getElementById(\'hidden_longitude\').value', $content);
        
        // Check that the location display element exists
        $this->assertStringContainsString('id="review-location"', $content);
        
        // Check that debugging information is included
        $this->assertStringContainsString('console.log(\'Session data loaded:\'', $content);
    }
    
    /** @test */
    public function it_verifies_form_submission_includes_location_data()
    {
        // This test verifies that the form in step4.blade.php
        // is set up to include location data in the submission
        
        // Read the step4 template
        $step4Path = resource_path('views/prestataire/urgent-sales/steps/step4.blade.php');
        $this->assertFileExists($step4Path);
        
        $content = file_get_contents($step4Path);
        
        // Check that the form exists and points to the correct route
        $this->assertStringContainsString('<form action="{{ route(\'prestataire.urgent-sales.store\') }}"', $content);
        $this->assertStringContainsString('method="POST"', $content);
        $this->assertStringContainsString('id="urgent-sale-form"', $content);
        
        // Check that all required hidden fields are present
        $this->assertStringContainsString('name="location"', $content);
        $this->assertStringContainsString('name="latitude"', $content);
        $this->assertStringContainsString('name="longitude"', $content);
        
        // Check that the publish button exists and has the correct ID
        $this->assertStringContainsString('id="final-publish-btn"', $content);
        
        // Check that the button has event listeners
        $this->assertStringContainsString('addEventListener(\'click\'', $content);
        $this->assertStringContainsString('form.submit()', $content);
    }
    
    /** @test */
    public function it_verifies_map_initialization_code_exists()
    {
        // This test verifies that the map initialization code exists
        // to display the location on the review page
        
        // Read the step4 template
        $step4Path = resource_path('views/prestataire/urgent-sales/steps/step4.blade.php');
        $this->assertFileExists($step4Path);
        
        $content = file_get_contents($step4Path);
        
        // Check that map container exists
        $this->assertStringContainsString('id="review-map"', $content);
        $this->assertStringContainsString('id="map-container"', $content);
        
        // Check that map initialization function exists
        $this->assertStringContainsString('function initializeMap()', $content);
        
        // Check that the function uses Leaflet
        $this->assertStringContainsString('L.map', $content);
        $this->assertStringContainsString('L.tileLayer', $content);
        $this->assertStringContainsString('L.marker', $content);
        
        // Check that the function uses session data for coordinates
        $this->assertStringContainsString('sessionData.latitude', $content);
        $this->assertStringContainsString('sessionData.longitude', $content);
    }
}