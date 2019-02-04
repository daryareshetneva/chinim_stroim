<?php

require_once APPLICATION_PATH . '/../.lib/PHPExcel/PHPExcel.php';

class Shop_Model_Import {

    private $_file = '';
    protected $_uploadDir = 'files/';
    protected $_filename = '';

    public function setFile($file) {
        $this->_file = $file;
    }

    public function setFilename($filename) {
        $this->_filename = $filename;
    }

    public function import() {
        try {
            $this->_uploadImportedFile();

            $data = $this->_parseImportedFile($this->_uploadDir . $this->_filename);

            $this->_saveItems($data);

            $this->_deleteImportedFile();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    /**
     * @param array $data
     * TODO! Make refactoring
     */
    private function _saveItems($data) {
        $categoriesTable = new Shop_Model_DbTable_Categories();
        $transliterateModel = new Model_Transliterate();
        $productsTable = new Shop_Model_DbTable_Products();

        $importedCategories = $data['categories'];
        $importedProducts = $data['products'];
        $categoriesIds = []; // array of title=>id categories

        $existCategories = $categoriesTable->getAllCategories();
        $existProducts = $productsTable->getAllProducts();


        if (empty($existCategories)) {
            // all categories are new
            foreach ($importedCategories as $importedCategoryTitle => $importedSubCategories) {
                $parentId = $categoriesTable->insert([
                    'title' => $importedCategoryTitle,
                    'alias' => $transliterateModel->transliterate($importedCategoryTitle),
                    'parentId' => 0
                ]);
                $categoriesIds[$importedCategoryTitle] = $parentId;
                foreach ($importedSubCategories as $subCategoryTitle => $subCategory) {
                    $subCategoryId = $categoriesTable->insert([
                        'title' => $subCategoryTitle,
                        'alias' => $transliterateModel->transliterate($subCategoryTitle),
                        'parentId' => $parentId
                    ]);
                    $categoriesIds[$subCategoryTitle] = $subCategoryId;
                    foreach($subCategory as $subSubCategoryTitle => $subSubCategory) {
                        $subSubCategoryId = $categoriesTable->insert([
                            'title' => $subSubCategoryTitle,
                            'alias' => $transliterateModel->transliterate($subSubCategoryTitle),
                            'parentId' => $subCategoryId
                        ]);
                        $categoriesIds[$subSubCategoryTitle] = $subSubCategoryId;
                    }
                }
            }
        } else {
            // Check for subSubCategories
            foreach ($existCategories as $existCategory) {
                foreach ($importedCategories as $subCategories) {
                    foreach ($subCategories as $subSubCategory) {
                        if (isset($subSubCategory[$existCategory['title']])) {
                            $categoriesIds[$existCategory['title']] = $existCategory['id'];
                            unset($subSubCategory[$existCategory['title']]);
                        }
                    }
                }
            }

            // Check for subcategories
            foreach ($existCategories as $existCategory) {
                foreach ($importedCategories as $subCategories) {
                    if (isset($subCategories[$existCategory['title']])) {
                        $categoriesIds[$existCategory['title']] = $existCategory['id'];
                        unset($subCategories[$existCategory['title']]);
                    }
                }
            }

            // Check for main categories
            foreach ($existCategories as $existCategoryKey => $existCategory) {
                if (isset($importedCategories[$existCategory['title']])) {
                    $categoriesIds[$existCategory['title']] = $existCategory['id'];
                    unset($importedCategories[$existCategory['title']]);
                }
            }

            foreach ($importedCategories as $importedCategoryTitle => $importedSubCategories) {
                $parentId = $categoriesTable->insert([
                    'title' => $importedCategoryTitle,
                    'alias' => $transliterateModel->transliterate($importedCategoryTitle),
                    'parentId' => 0
                ]);
                $categoriesIds[$importedCategoryTitle] = $parentId;
                foreach ($importedSubCategories as $subCategoryTitle) {
                    $subCategoryId = $categoriesTable->insert([
                        'title' => $subCategoryTitle,
                        'alias' => $transliterateModel->transliterate($subCategoryTitle),
                        'parentId' => $parentId
                    ]);
                    $categoriesIds[$subCategoryTitle] = $subCategoryId;
                }
            }

        }

        if (empty($existProducts)) {
            // all products are new
            foreach ($importedProducts as $importedProduct) {
                $productCategoryId = $categoriesIds[$importedProduct['mainCategory']];
                $productCategory = $importedProduct['mainCategory'];
                if (isset($importedProduct['subCategory'])) {
                    $productCategoryId = $categoriesIds[$importedProduct['subCategory']];
                    $productCategory = $importedProduct['subCategory'];
                }
                if (isset($importedProduct['subSubCategory'])) {
                    $productCategoryId = $categoriesIds[$importedProduct['subSubCategory']];
                    $productCategory = $importedProduct['subSubCategory'];
                }

                $productsTable->insert([
                    'title' => $importedProduct['title'],
                    'price' => $importedProduct['price'],
                    'image' => '',
                    'description' => '',
                    'discount' => 0,
                    'titleSearch' => '',
                    'alias' => $transliterateModel->transliterate($importedProduct['title']),
                    'categoryId' => $productCategoryId,
                    'count' => 100,
                    'deleted' => 0,
                    'sale' => 0,
                    'metaTitle' => $importedProduct['title'] . ' | ' . $productCategory,
                    'metaKeywords' => '',
                    'metaDescription' => ''
                ]);
            }
        } else {
            foreach ($existProducts as $existProduct) {
                foreach ($importedProducts as $importedProductKey => $importedProduct) {
                    if ($existProduct['title'] == $importedProduct['title']) {

                        $productCategoryId = $categoriesIds[$importedProduct['mainCategory']];
                        $productCategory = $importedProduct['mainCategory'];
                        if (isset($importedProduct['subCategory'])) {
                            $productCategoryId = $categoriesIds[$importedProduct['subCategory']];
                            $productCategory = $importedProduct['subCategory'];
                        }
                        $updateData = [
                            'price' => $importedProduct['price'],
                            'categoryId' => $productCategoryId,
                            'metaTitle' => $importedProduct['title'] . ' | ' . $productCategory
                        ];
                        $where = ['id = ?' => $existProduct['productId']];
                        $productsTable->update($updateData, $where);
                        unset($importedProducts[$importedProductKey]);
                    }
                }
            }

            foreach ($importedProducts as $importedProduct) {
                $productCategoryId = $categoriesIds[$importedProduct['mainCategory']];
                $productCategory = $importedProduct['mainCategory'];
                if (isset($importedProduct['subCategory'])) {
                    $productCategoryId = $categoriesIds[$importedProduct['subCategory']];
                    $productCategory = $importedProduct['subCategory'];
                }
                $productsTable->insert([
                    'title' => $importedProduct['title'],
                    'price' => $importedProduct['price'],
                    'image' => '',
                    'description' => '',
                    'discount' => 0,
                    'titleSearch' => '',
                    'alias' => $transliterateModel->transliterate($importedProduct['title']),
                    'categoryId' => $productCategoryId,
                    'count' => 100,
                    'deleted' => 0,
                    'sale' => 0,
                    'metaTitle' => $importedProduct['title'] . ' | ' . $productCategory,
                    'metaKeywords' => '',
                    'metaDescription' => ''
                ]);
            }

        }


    }

    private function _uploadImportedFile() {
        if (!move_uploaded_file($this->_file, $this->_uploadDir . $this->_filename)) {
            throw new Exception('failedToUploadFile');
        }
    }

    private function _deleteImportedFile() {
        unlink($this->_uploadDir . $this->_filename);
    }

    private function _parseImportedFile($filename) {
        $categories = [];
        $products = [];



        return ['categories' => $categories, 'products' => $products];
    }

}
