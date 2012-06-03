<?php
defined('_JEXEC') or die;
if(JRequest::getVar('token')){
    ?>
        <p>Gracias <a href="index.php"> Continuar</a></p>
    <?php    
}
elseif($_COOKIE['sessionkey']){
    ?>
<script>
    ajaxpage('index.php?option=com_lastfm&task=lastfm.buscarEventos','eventos');
</script>    

    <b>Usuario:</b> <?php echo $_COOKIE['username'];?><br /></br>
    <form action="index.php?option=com_lastfm&task=lastfm.cerrarSesion" method="get" >
        <input type="submit"  name="salir" class="button" value="Salir" />
        <input type="hidden" name="task" value="lastfm.cerrarSesion" />
    </form>    
    </br></br>
    <div id="eventos"></div></br>
    <?php
}else{
    ?>
        <p><a href="http://www.last.fm/api/auth/?api_key=dad76eb8ec6c0a16d86ab23f79d6b3d1">use su cuenta</a>.</p>
<?php }?>

