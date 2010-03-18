<?php
/* 
Plugin Name: No Etiqueta en Inicio 
Plugin URI: http://flasheves.com 
Description: Hace que no se muestren en inicio (home) los post de las etiquetas definidas
Version: 1.0 
Author: eveevans
Author URI: http://flasheves.com
*/  

// Hook para eliminar post etiquetas del home
add_action('home_template', 'eliminiar_etiquetas_home');

// Opciones del menu
add_action('admin_menu', 'menu_etiqueta_no_inicio');


//Filtro para las eliminar post con etiquetas definidas en el home
function eliminiar_etiquetas_home () {
	
	$eeh_opciones=get_option('n_etiquetas');
	if(	$eeh_opciones != "")
		{
			$post_excluidos = array();
			$post_filtrados = get_posts("numberposts=-1&tag=".$eeh_opciones);	
				
			foreach($post_filtrados as $pf) :
				$post_excluidos[]=$pf->ID;
				//echo $pf->post_title;
			endforeach;
					
			 $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			query_posts(array(
				'paged' =>  $paged,
				'post__not_in' => $post_excluidos)
			);			
		}
}


function menu_etiqueta_no_inicio() {

	//Agregar menu
	add_menu_page('No Etiqueta Inicio', 'No Etiquetas', 'administrator', __FILE__, 'no_etiqueta_inicio',plugins_url('/images/icon.png', __FILE__));

	//Llamar Acciones del Register Settings
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//Registrar Opciones (register settings) 
	register_setting( 'etiqueta_no_inicio_settings', 'n_etiquetas' );
}

function no_etiqueta_inicio() {
?>
<div class="wrap">
<h2>Etiquetas que no apareceran en inicio</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'etiqueta_no_inicio_settings' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Nombre de las Etiquetas (separados por coma) : </th>
        <td><input type="text" name="n_etiquetas" value="<?php echo get_option('n_etiquetas'); ?>" /></td>
        </tr>
         
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>
