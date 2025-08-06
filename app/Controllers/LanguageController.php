<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class LanguageController extends BaseController
{
    /**
     * Available languages configuration
     */
    private array $availableLanguages = [
        'en' => [
            'name' => 'English',
            'flag' => 'us.svg',
            'code' => 'en',
            'locale' => 'en_US'
        ],
        'es' => [
            'name' => 'Español',
            'flag' => 'spain.svg',
            'code' => 'es',
            'locale' => 'es_ES'
        ],
        'pt' => [
            'name' => 'Português',
            'flag' => 'br.svg',
            'code' => 'pt',
            'locale' => 'pt_BR'
        ]
    ];

    public function index()
    {
        $data = [
            'languages' => $this->availableLanguages,
            'current_language' => session()->get('locale') ?? 'en'
        ];
        
        return view("example_translations", $data);
    }
    
    public function setLanguage($lang = "en")
    {
        try {
        // Map old codes to new standardized codes
        $langMapping = [
            'sp' => 'es',  // Map 'sp' to 'es'
            'br' => 'pt'   // Map 'br' to 'pt' if needed
        ];
        
        // Apply mapping if exists
        if (isset($langMapping[$lang])) {
            $lang = $langMapping[$lang];
        }
        
        // Validate language code
        if (!array_key_exists($lang, $this->availableLanguages)) {
            $lang = "en"; // Default to English if invalid
        }
        
        // Set language in session
        session()->set("locale", $lang);
        
        // Also set for CodeIgniter's Localization service
        $request = \Config\Services::request();
        $request->setLocale($lang);
        
        $response = [
            'success' => true,
            'language' => $lang,
            'language_info' => $this->availableLanguages[$lang],
            'message' => 'Language changed to ' . $this->availableLanguages[$lang]['name'],
            'frontend_translations' => $this->getFrontendTranslations($lang),
            'session_locale' => session()->get('locale')
        ];
        
        // If it's an AJAX request, return JSON with additional info
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($response);
        }
        
        // For non-AJAX requests, set flash message and redirect
        session()->setFlashdata('success', $response['message']);
        return redirect()->back();
        } catch (\Exception $e) {
            log_message('error', 'Language change error: ' . $e->getMessage());
            
            $errorResponse = [
                'success' => false,
                'message' => 'Error changing language: ' . $e->getMessage()
            ];
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON($errorResponse);
            }
            
            session()->setFlashdata('error', 'Error changing language');
            return redirect()->back();
        }
    }
    
    /**
     * Get available languages for frontend
     */
    public function getAvailableLanguages()
    {
        return $this->response->setJSON([
            'success' => true,
            'languages' => $this->availableLanguages,
            'current' => session()->get('locale') ?? 'en'
        ]);
    }
    
    /**
     * Get current language info
     */
    public function getCurrentLanguage()
    {
        $currentLang = session()->get('locale') ?? 'en';
        
        return $this->response->setJSON([
            'success' => true,
            'current_language' => $currentLang,
            'language_info' => $this->availableLanguages[$currentLang] ?? $this->availableLanguages['en'],
            'session_locale' => session()->get('locale')
        ]);
    }
    
    /**
     * Get frontend translations for JavaScript
     */
    public function getFrontendTranslations($lang = null)
    {
        if (!$lang) {
            $lang = session()->get('locale') ?? 'en';
        }
        
        // Path to frontend translations
        $jsonPath = FCPATH . 'assets/lang/' . $lang . '.json';
        
        if (file_exists($jsonPath)) {
            $translations = json_decode(file_get_contents($jsonPath), true);
            return $translations;
        }
        
        return [];
    }
    
    /**
     * AJAX endpoint to get frontend translations
     */
    public function getTranslationsJson($lang = null)
    {
        if (!$lang) {
            $lang = $this->request->getGet('lang') ?? session()->get('locale') ?? 'en';
        }
        
        $translations = $this->getFrontendTranslations($lang);
        
        return $this->response->setJSON([
            'success' => true,
            'language' => $lang,
            'translations' => $translations
        ]);
    }
}