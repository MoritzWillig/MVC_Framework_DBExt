<?php
class PageModel extends Model  {
  
	public function __construct() {
		parent::__construct();
    
    $this->DB=load_model("database");
	}
  
  public function searchByPath($pageType,$alias,$controller,$function,$view) {
    //find matching entries. prefer aliases over standart paths and catchall entries
    $this->DB->query('
      SELECT
        *
      FROM
        rol_pages
      WHERE
        pageType="%s"
      AND
        (
          (
            controller="%s"
          AND
            ((function="%s") OR (function="_catchall"))
          AND 
            ((view="%s") OR (view="_catchall"))
          )
        OR 
          (alias="%s")
        ) 
      ORDER BY 
        (alias<>"") DESC, 
        (function="__catchall") ASC,
        (view="__catchall") ASC',
      $pageType,$controller,$function,$view,$alias);
    return $this->DB->get_all_rows();
  }
  
}
?>