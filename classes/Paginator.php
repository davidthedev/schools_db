<?php

class Paginator
{
    public $currentPage;
    public $rowsPerPage;
    private $totalRowsFromDb;
    private $offset;

    /**
     * Set total number of rows fetched from a database
     *
     * @param void
     */
    public function setTotalRowsFromDb($totalRowsFromDb)
    {
        $this->totalRowsFromDb = $totalRowsFromDb;
    }

    /**
     * Get total number of rows fetched form a database
     *
     * @return int
     */
    public function getTotalRowsFromDb()
    {
        return $this->totalRowsFromDb;
    }

    /**
     * Set how many rows to display
     *
     * @param void
     */
    public function setRowsPerPage($rowsPerPage)
    {
        $this->rowsPerPage = $rowsPerPage;
    }

    /**
     * Get the number of rows to display on a page
     *
     * @return int
     */
    public function getRowsPerPage()
    {
        return $this->rowsPerPage;
    }

    /**
     * Set current page
     *
     * @param void
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * Get search offset
     *
     * @return int
     */
    public function getSearchOffset()
    {
        if($this->getRowsPerPage() != 0) {
            return (($this->currentPage * $this->rowsPerPage) - $this->rowsPerPage);
        } else {
            return 0;
        }
    }

    /**
     * Get the total number of pagination pages
     *
     * @return int
     */
    public function getTotalPages()
    {
        if($this->getRowsPerPage() != 0) {
            return ceil($this->getTotalRowsFromDb() / $this->rowsPerPage);
        } else {
            return 0;
        }
    }
}
