<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Rf_List_Table extends WP_List_Table {
    
    public function get_columns()
    {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'id' => 'Id',
            'departing_from' => 'Departinf from',
            'going_to' => 'Going to',
            'phone' => 'Phone',
            'created' => 'Created at'
        );
        return $columns;
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->getRequestFormData();
    }

    public function column_default($item, $column_name)
    {
        switch( $column_name ) { 
            case 'id':
            case 'departing_from':
            case 'going_to':
            case 'phone':
            case 'created':
                return $item[ $column_name ];
            default:
                return print_r( $item, true );
        }
    }
    
    public function getRequestFormData()
    {
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';
        global $wpdb, $scrf;
        return $wpdb->get_results( "SELECT * FROM `{$scrf['tableName']}` ORDER BY `{$orderby}` {$order};", ARRAY_A);
    }
    
    public function get_sortable_columns() {
        $sortable_columns = array(
            'id'  => array('id', false),
            'departing_from' => array('departing_from', false),
            'going_to'   => array('going_to', false),
            'phone'   => array('phone', false),
            'created'   => array('created', false)
        );
        return $sortable_columns;
    }
    
function column_id($item) {
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
             );
  return sprintf('%1$s %2$s', $item['id'], $this->row_actions($actions) );
}
    
    public function get_bulk_actions() {
        $actions = array(
            'edit' => 'Edit',
            'delete'  => 'Delete'
        );
        return $actions;
    }
    
    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="book[]" value="%s" />', $item['ID']);    
    }
}

?>
