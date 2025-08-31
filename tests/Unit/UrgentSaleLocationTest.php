<?php

namespace Tests\Unit;

use Tests\TestCase;

class UrgentSaleLocationTest extends TestCase
{
    /** @test */
    public function it_verifies_location_data_is_handled_in_step4_template()
    {
        // This test verifies that the location data is properly handled in the step4 template
        // by checking that the necessary HTML elements exist
        
        // We'll check the content of the step4.blade.php file directly
        $step4Path = resource_path('views/prestataire/urgent-sales/steps/step4.blade.php');
        
        // Check if the file exists
        $this->assertFileExists($step4Path);
        
        // Read the file content
        $content = file_get_contents($step4Path);
        
        // Check that hidden fields for location data exist
        $this->assertStringContainsString('name="location"', $content);
        $this->assertStringContainsString('name="latitude"', $content);
        $this->assertStringContainsString('name="longitude"', $content);
        
        // Check that the JavaScript code to populate location data exists
        $this->assertStringContainsString('sessionData.location', $content);
        $this->assertStringContainsString('sessionData.latitude', $content);
        $this->assertStringContainsString('sessionData.longitude', $content);
        
        // Check that the map initialization code exists
        $this->assertStringContainsString('initializeMap()', $content);
        $this->assertStringContainsString('L.map', $content);
        
        // Check that the updateReviewStep function exists and handles location data
        $this->assertStringContainsString('updateReviewStep()', $content);
        $this->assertStringContainsString('document.getElementById(\'review-location\')', $content);
    }
}