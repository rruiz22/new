<?php

namespace Modules\PublicPages\Models;

use CodeIgniter\Model;

class PublicPageVersionModel extends Model
{
    protected $table = 'public_page_versions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'page_id',
        'version_number',
        'title',
        'content',
        'changes_summary',
        'created_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'page_id' => 'integer',
        'version_number' => 'integer',
        'created_by' => 'integer'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'page_id' => 'required|integer',
        'version_number' => 'required|integer',
        'title' => 'required|string|max_length[255]',
        'content' => 'required|string',
        'created_by' => 'required|integer'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get versions for a page
     */
    public function getPageVersions(int $pageId, int $limit = 10, int $offset = 0)
    {
        return $this->select('public_page_versions.*, users.first_name, users.last_name, users.username')
                   ->join('users', 'users.id = public_page_versions.created_by', 'left')
                   ->where('page_id', $pageId)
                   ->orderBy('version_number', 'DESC')
                   ->limit($limit, $offset)
                   ->findAll();
    }

    /**
     * Get specific version
     */
    public function getVersion(int $pageId, int $versionNumber)
    {
        return $this->where('page_id', $pageId)
                   ->where('version_number', $versionNumber)
                   ->first();
    }

    /**
     * Get latest version
     */
    public function getLatestVersion(int $pageId)
    {
        return $this->where('page_id', $pageId)
                   ->orderBy('version_number', 'DESC')
                   ->first();
    }

    /**
     * Restore version
     */
    public function restoreVersion(int $pageId, int $versionNumber, int $userId)
    {
        $version = $this->getVersion($pageId, $versionNumber);
        if (!$version) {
            return false;
        }

        $pageModel = new PublicPageModel();
        $currentPage = $pageModel->find($pageId);
        if (!$currentPage) {
            return false;
        }

        // Update the page with version content
        $updateData = [
            'title' => $version['title'],
            'content' => $version['content'],
            'updated_by' => $userId
        ];

        return $pageModel->update($pageId, $updateData);
    }

    /**
     * Compare versions
     */
    public function compareVersions(int $pageId, int $version1, int $version2)
    {
        $v1 = $this->getVersion($pageId, $version1);
        $v2 = $this->getVersion($pageId, $version2);

        if (!$v1 || !$v2) {
            return false;
        }

        return [
            'version1' => $v1,
            'version2' => $v2,
            'title_diff' => $this->generateDiff($v1['title'], $v2['title']),
            'content_diff' => $this->generateDiff($v1['content'], $v2['content'])
        ];
    }

    /**
     * Generate simple diff between two texts
     */
    private function generateDiff(string $text1, string $text2)
    {
        // This is a simple implementation. For more advanced diff,
        // consider using a library like sebastianbergmann/diff
        
        $lines1 = explode("\n", $text1);
        $lines2 = explode("\n", $text2);
        
        $diff = [];
        $maxLines = max(count($lines1), count($lines2));
        
        for ($i = 0; $i < $maxLines; $i++) {
            $line1 = $lines1[$i] ?? '';
            $line2 = $lines2[$i] ?? '';
            
            if ($line1 !== $line2) {
                $diff[] = [
                    'line' => $i + 1,
                    'old' => $line1,
                    'new' => $line2,
                    'type' => empty($line1) ? 'added' : (empty($line2) ? 'removed' : 'modified')
                ];
            }
        }
        
        return $diff;
    }

    /**
     * Clean old versions (keep only latest N versions)
     */
    public function cleanOldVersions(int $pageId, int $keepVersions = 10)
    {
        $versions = $this->where('page_id', $pageId)
                        ->orderBy('version_number', 'DESC')
                        ->findAll();

        if (count($versions) > $keepVersions) {
            $versionsToDelete = array_slice($versions, $keepVersions);
            foreach ($versionsToDelete as $version) {
                $this->delete($version['id']);
            }
        }

        return true;
    }
}
