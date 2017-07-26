<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-screen.php' );//added
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );//added
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    require_once( ABSPATH . 'wp-admin/includes/template.php' );
}
class Magenest_Giftregistry_Admin extends WP_List_Table{
		/**
		 * Constructor.
		 */
	public function __construct() {
						
				parent::__construct( [
					'singular' => __( 'Registry', 'sp' ), //singular name of the listed records
					'plural'   => __( 'Registries', 'sp' ), //plural name of the listed records
					'ajax'     => false //should this table support ajax?
				] );
	}

		/**
		 * Retrieve Registry's data from the database
		 *
		 * @param int $per_page
		 * @param int $page_number
		 *
		 * @return mixed
		 */
		public static function get_registries( $per_page = 20, $page_number = 1 ) {

		  global $wpdb;

		  $sql = "SELECT * FROM {$wpdb->prefix}magenest_giftregistry_wishlist";

		  if (!empty( $_REQUEST['orderby'] ) ) {
		    $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
		    $sql .= !empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		  }

		  $sql .= " LIMIT $per_page";

		  $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		  $result = $wpdb->get_results( $sql, 'ARRAY_A' );

		  return $result;
		}

		/**
		 * Delete a registry record.
		 *
		 * @param int $id registry ID
		 */
		public static function delete_registry( $id ) {
		  global $wpdb;

		  $wpdb->delete(
		    "{$wpdb->prefix}magenest_giftregistry_wishlist",
		    [ 'id' => $id ],
		    [ '%d' ]
		  );
		}

		/**
		 * Returns the count of records in the database.
		 *
		 * @return null|string
		 */
		public static function record_count() {
		  global $wpdb;

		  $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}magenest_giftregistry_wishlist";

		  return $wpdb->get_var( $sql );
		}

		/** Text displayed when no registry data is available */
		public function no_items() {
		  _e( 'No registries avaliable.', 'sp' );
		}

		/**
		 * Method for name column
		 *
		 * @param array $item an array of DB data
		 *
		 * @return string
		 */
		function column_name( $item ) {

		  // create a nonce
		  $delete_nonce = wp_create_nonce( 'sp_delete_giftregistry' );

		  $title = '<strong>' . $item['title'] . '</strong>';

		  $actions = [
		    'delete' => sprintf( '<a href="?page=%s&action=%s&registry=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
		  ];

		  return $title . $this->row_actions( $actions );
		}

		/**
		 * Render a column when no column specific method exists.
		 *
		 * @param array $item
		 * @param string $column_name
		 *
		 * @return mixed
		 */
		public function column_default( $item, $column_name ) {
		  switch ( $column_name ) {
		    case 'title':
		    case 'registrant_firstname':
		    case 'registrant_lastname':
		    case 'registrant_email':
		    case 'registrant_type':
		    case 'event_date_time';
		    case 'event_location';
		    case 'coregistrant_firstname':
		    case 'coregistrant_lastname':
		    case 'coregistrant_email':
		    case 'coregistrant_type':
		    case 'status';
		      return $item[ $column_name ];
		    default:
		      return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		  }
		}

		function single_row_columns($item) {
	       list($columns, $hidden) = $this->get_column_info();
	        foreach($columns as $column_name => $column_display_name) {
	                $class = "class='$column_name column-$column_name'";
	                $style = '';
	                if (in_array($column_name, $hidden)){
	                     $style = ' style="display:none;"';
	                }
	                $attributes = "$class$style";
	            if('cb' == $column_name){
	            	  

		        }elseif('title' == $column_name){
		           echo "<td $attributes>";
		           echo '<a id="" href="'.get_site_url() .'/wp-admin/admin.php?page=gift_registry&edit=1&id='.$item['id'].'">', $item['title'];
		           echo "</a>";
		           echo "<div class='row-actions'><span class='edit'>";
		           echo '<a href="'.get_site_url() .'/wp-admin/admin.php?page=gift_registry&edit=1&id='.$item['id'].'">Edit</a>';
		           echo "</span> | <span class='trash'>";
		           echo '<a href="'.get_site_url() .'/wp-admin/admin.php?page=gift_registry&delete=1&id='.$item['id'].'">Delete</a>';
		           echo "</span></div></td>";
				}else{
		            echo "<td $attributes>";
		            echo $this->column_default( $item, $column_name );
		            echo "</td>";
		        } 
		    } 
		} 

		/**
		 * Render the bulk edit checkbox
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_cb( $item ) {
		  return sprintf(
		    '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		  );
		}

		/**
		 *  Associative array of columns
		 *
		 * @return array
		 */
		function get_columns() {
		  $columns = [
		    //'cb'      					=> '<input type="checkbox" />',
		    'title'    					=> __( 'Title', 'sp' ),
		    'registrant_firstname' 		=> __( 'First Name', 'sp' ),
		    'registrant_lastname'   	=> __( 'Last Name', 'sp' ),
		    'registrant_email' 			=> __( 'Email', 'sp' ),
		    'registrant_type' 			=> __( 'Type', 'sp' ),
		    'event_date_time'    		=> __( 'Event Time', 'sp' ),
		    'event_location'    		=> __( 'Event Location', 'sp' ),
		    'coregistrant_firstname' 	=> __( 'Partner First Name', 'sp' ),
		    'coregistrant_lastname'    	=> __( 'Partner Last Name', 'sp' ),
		    'coregistrant_email' 		=> __( 'Partner Email', 'sp' ),
		    'coregistrant_type' 		=> __( 'Partner Type', 'sp' ),
		    'status'    				=> __( 'Status', 'sp' )
		  ];

		  return $columns;
		}

		/**
		 * Columns to make sortable.
		 *
		 * @return array
		 */
		public function get_sortable_columns() {
		  $sortable_columns = array(
		    'title' => array( 'title', true ),
		    'registrant_firstname' 		=> array( 'registrant_firstname', false ),
		    'registrant_lastname' 		=> array( 'registrant_lastname', false ),
		    'registrant_email' 			=> array( 'registrant_email', false ),
		    'registrant_type' 			=> array( 'registrant_type', true ),
		    'event_date_time'    		=> array( 'event_date_time', true ),
		    'event_location'    		=> array( 'event_location', true ),
		    'coregistrant_firstname' 	=> array( 'coregistrant_firstname', false),
		    'coregistrant_lastname'  	=> array( 'coregistrant_lastname', false ),
		    'coregistrant_email' 		=> array( 'coregistrant_email', false ),
		    'coregistrant_type' 		=> array( 'coregistrant_type', true ),
		    'status'    				=> array( 'status',true)
		  );

		  return $sortable_columns;
		}

		/**
		 * Returns an associative array containing the bulk action
		 *
		 * @return array
		 */
		public function get_bulk_actions() {
		  $actions = [
		    'bulk-delete' => 'Delete'
		  ];

		  return $actions;
		}
		public function display_tablenav($which){
			//echo '<pre>';
			//print_r($which); // top
			//echo '</pre>';

			?>
		<form action="" method="GET">
		    <?php 
		    $this->search_box( __( 'Search' ), 'search-box-id' ); 
		    ?>
		    <input type="hidden" name="page" value="<?= esc_attr($_REQUEST['page']) ?>"/>
		</form>
		<form action="" method="GET">
			<input type="submit" value="Show All" class="btn button btn-primary">
			<input type="hidden" name="all" value="all"/>
		    <input type="hidden" name="page" value="<?= esc_attr($_REQUEST['page']) ?>"/>
		</form>
		<?php
		}

		/**
		 * Handles data query and filter, sorting, and pagination.
		 */
		public function prepare_items() {
			global $wpdb;
			$all = ( isset( $_REQUEST['all'] ) ) ? $_REQUEST['all'] : false;
			if($all != false){
				  $search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
				  $do_search = ( $search ) ? "title LIKE '%$search%' OR registrant_firstname LIKE '%$search%' OR coregistrant_firstname LIKE '%$search%' OR registrant_email LIKE '%$search%' " : ''; 
				  $sql_query = "SELECT * FROM {$wpdb->prefix}magenest_giftregistry_wishlist WHERE $do_search";
				  $sql_results = $wpdb->get_results($sql_query,ARRAY_A );
			}

		  $this->_column_headers = $this->get_column_info();

		  /** Process bulk action */
		  $this->process_bulk_action();

		  $per_page     = $this->get_items_per_page( 'registries_per_page', 20 );
		  $current_page = $this->get_pagenum();
		  $total_items  = self::record_count();

		  $this->set_pagination_args( [
		    'total_items' => $total_items, //WE have to calculate the total number of items
		    'per_page'    => $per_page //WE have to determine how many items to show on a page
		  ] );
		  if(isset($search) && $search != false){
		  	$this->items = $sql_results;
		  }else{
			  $this->items = self::get_registries( $per_page, $current_page );
		  }
		}

		public function process_bulk_action() {

		  //Detect when a bulk action is being triggered...
		  if ( 'delete' === $this->current_action() ) {

		    // In our file that handles the request, verify the nonce.
		    $nonce = esc_attr( $_REQUEST['_wpnonce'] );

		    if ( ! wp_verify_nonce( $nonce, 'sp_delete_giftregistry' ) ) {
		      die( 'Go get a life script kiddies' );
		    }
		    else {
		      self::delete_registry( absint( $_GET['registry'] ) );

		      wp_redirect( esc_url( add_query_arg() ) );
		      exit;
		    }

		  }

		  // If the delete bulk action is triggered
		  if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		       || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		  ) {

		    $delete_ids = esc_sql( $_POST['bulk-delete'] );

		    // loop over the array of record IDs and delete them
		    foreach ( $delete_ids as $id ) {
		      self::delete_registry( $id );

		    }

		    wp_redirect( esc_url( add_query_arg() ) );
		    exit;
		  }
		}
	public function giftregistry_manage() {
		if (isset ( $_REQUEST ['delete'] )) {
			if (isset ( $_REQUEST ['id'] )){
				$this->delete( $_REQUEST ['id'] );
			}
		} elseif (isset ( $_REQUEST ['edit'] )) {
			if (isset ( $_REQUEST ['id'] )){
				$this->edit( $_REQUEST ['id'] );
			}
		} else {
			$this->index ();
		}
	}
	public function giftregistry_manages() {
		$rows_per_page = 10;
		$current = (intval(get_query_var('paged'))) ? intval(get_query_var('paged')) : 1;
		
		//$rows = $wpdb->get_results('SELECT * FROM subscriber ORDER BY sub_lname ASC');
		
		$rows = Magenest_Giftregistry_Model::get_all_giftregistry();
		$start = ($current - 1) * $rows_per_page;
		$end = $start + $rows_per_page;
		$end = (sizeof($rows) < $end) ? sizeof($rows) : $end;
		
		$pagination_args = array(
				'base' => esc_url_raw(@add_query_arg('paged','%#%')),
				'format' => '?page=%#%',
				'total' => ceil(sizeof($rows)/$rows_per_page) + 1,
				'current' => $current,
				'show_all' => False,
				'prev_next'    => True,
				'prev_text'    => __(' Previous'),
				'next_text'    => __('Next '),
				'type' => 'plain',
				'add_args'     => False
		);
		
		echo paginate_links($pagination_args);
	}
	
	public static function index() {
		// Test the use of paginate_links
		
		$rows_per_page = 10;
		
		$current = (isset($_REQUEST['paged'])&&intval($_REQUEST['paged']) ) ? intval($_REQUEST['paged']) : 1;
		
		// $rows is the array that we are going to paginate.
        $rows = Magenest_Giftregistry_Model::get_all_giftregistry();
		
		$max_page = ceil(sizeof($rows)/$rows_per_page);

		global $wp_rewrite,$wp_query;
		
		$pagination_args = array(
				'base' => esc_url_raw(@add_query_arg('paged','%#%')),
				'format' => '',
				'total' => ceil(sizeof($rows)/$rows_per_page),
				'current' => $current,
				'show_all' => false,
				'type' => 'plain',
		);
		
		//if( $wp_rewrite->using_permalinks() )
		//	$pagination_args['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'page/%#%/', 'paged');
		
		if( !empty($wp_query->query_vars['s']) )
			$pagination_args['add_args'] = array('s'=>get_query_var('s'));
		
		$start = ($current - 1) * $rows_per_page;
		$end = $start + $rows_per_page;
		$end = (sizeof($rows) < $end) ? sizeof($rows) : $end;
		

		?>
<div class="wrap">
		<h2>Registries:</h2>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
<table id="wishlit-tbl" class="wp-list-table widefat fixed">
<thead>
	<tr>
		<th id="columnUserID" class="manage-column column-columnUserID" scope="col">
	 	<?php echo __('User id')?>
	 	</th>
		<th id="columnname" class="manage-column column-columnname" scope="col">
	 	<?php echo __('Registrant name')?>
	 	</th>
		<th id="columnnemail" class="manage-column column-columnname" scope="col">
	 	<?php echo __('Registrant email')?>
	 	</th>
		<th id="columnnTime" class="manage-column column-columnname" scope="col">
	 	<?php echo __('Date Time')?>
	 	</th>
		<th id="columnnActionDelete" class="manage-column column-columnname" scope="col">
	 	<?php echo __('Delete')?>
	 	</th>
		<th id="columnnActionEdit" class="manage-column column-columnname" scope="col">
	 	<?php echo __('Edit')?>
	 	</th>
	</tr>
</thead>
<?php 
	for ($i=$start;$i < $end ;++$i ) {
		$row = $rows[$i];
		$phpdate = strtotime( $row['event_date_time'] );
		$order_date = date('d M, Y h:i A', $phpdate);
		$http_schema = 'http://';
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
				$http_schema = 'https://';
			}
		$delete_link = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '&delete=1';
		$edit_link = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '&edit=1';
		echo '<tr class="alternate">';
?>
	<td class="column-columnname"><?php echo $row['user_id']?> </td>
	<td class="column-columnname"><?php echo $row['registrant_firstname']?> </td>
	<td class="column-columnname"><?php echo $row['registrant_email']?> </td>
	<td class="column-columnname"><?php echo $order_date ?> </td>
	<td class="column-columnname">
        <div class="row-actions-show">
            <span>
	            <a href="<?php echo $delete_link.'&id='.$row['id'] ?>">
	            	<?php echo __('Delete', GIFTREGISTRY_TEXT_DOMAIN)?>
	            </a>
            </span>
        </div>
    </td>
    <td class="column-columnname">
        <div class="row-actions-show">
            <span>
	            <a href="<?php echo $edit_link.'&id='.$row['id'] ?>">
	            	<?php echo __('Edit', GIFTREGISTRY_TEXT_DOMAIN)?>
	            </a>
            </span>
        </div>
    </td>

<?php 
	echo '</tr>';	
	}
?>
</table>
<div class="tablenav-pages">
	<span class="pagination-links">
		<?php
			echo paginate_links($pagination_args);
		?>
	</span>
</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>	
</div>
<?php
	}
	
	public function delete($id) {
		Magenest_Giftregistry_Model::delete_giftregistry($id);
	}
	public function edit($id) {
		?>
		<div class="redux-container">
		<br>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://wrapistry.shop/wp-content/plugins/redux-framework/ReduxCore/assets/css/redux-admin.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript" charset="utf-8" async defer></script>
		<link rel="stylesheet" href="https://wrapistry.shop/wp-content/plugins/redux-framework/ReduxCore/assets/css/redux-fields.css">
		<link rel="stylesheet" href="https://wrapistry.shop/wp-content/plugins/redux-framework/ReduxCore/assets/css/vendor/elusive-icons/elusive-icons.css">
		<button onclick="window.location.href='<?php echo get_admin_url(null,'admin.php?page=gift_registry')?>'" name="back" type="button" class="button button-primary button-large" id="back" accesskey="p" value="Back"><?php echo  __('Gift registry manage')?> </button>
		
		<div id="redux-header">
		    <div class="display_header">
		        <h2>Gift registry</h2>
		        <span>1.7</span>   
		    </div>
		    <div class="clear"></div>
		</div>
		<div class="redux-sidebar">
	    <ul class="nav nav-pills redux-group-menu">
			<li id="1_section_group_li" class="redux-group-tab-link-li active">
				<a  href="#1a" data-toggle="tab"><i class="el el-home"></i> 
				<span class="group_title">General</span>
				</a>
			</li>
			<li id="2_section_group_li" class="redux-group-tab-link-li">
				<a href="#2a" data-toggle="tab"><i class="el el-th"></i> 
				<span class="group_title">Header</span>
				</a>
			</li>
			<li id="2_section_group_li" class="redux-group-tab-link-li">
				<a href="#3a" data-toggle="tab"><i class="el el-photo"></i> 
				<span class="group_title">Banners</span>
				</a>
			</li>
		</ul>
	</div>
	<div class="redux-main tab-content clearfix"> 
		<?php
		ob_start();
		$template_path = GIFTREGISTRY_PATH.'template/account/';
		$default_path = GIFTREGISTRY_PATH.'template/account/';
		
		wc_get_template( 'add-giftregistry.php', array(
		'wid' 		=>$id,
		
		),$template_path,$default_path
		);
		echo  ob_get_clean();
		
		/////////////////////////////////////////////////////////////////////
		/////////////////////////////GIFT REGISTRY ITEMS/////////////////////
		////////////////////////////////////////////////////////////////////
		$items = Magenest_Giftregistry_Model::get_items_in_giftregistry($id);
		ob_start();
		
		$template_path = GIFTREGISTRY_PATH.'template/account/';
		$default_path = GIFTREGISTRY_PATH.'template/account/';
		wc_get_template( 'my-giftregistry.php', array(
		'items' 		=>$items,
		'wid' 		=>$id
		),$template_path,$default_path
		);?>
		</div><!--main-->
		</div>
		<?php
		echo  ob_get_clean();
	}
		
}