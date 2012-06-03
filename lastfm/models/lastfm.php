<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
 
class LastfmModelLastfm extends JModelForm
{
    protected $api_key = 'dad76eb8ec6c0a16d86ab23f79d6b3d1';
    protected $secret = 'd8fc7f6be2d403de2b87ddb0b8bb6406';
    
    public function obtenerDatos() {
        
        require 'lastfmapi/lastfmapi.php';

        if ( isset($_COOKIE['sessionkey']) && isset($_COOKIE['username']) && isset($_COOKIE['subscriber'])  ) {
	$vars = array(
		'apiKey' => $this->api_key,
		'secret' => $this->secret,
		'username' => $_COOKIE['username'],
		'sessionKey' => $_COOKIE['sessionkey'],
		'subscriber' => $_COOKIE['subscriber']
	);
        
        $lastfmapi_auth = new lastfmApiAuth('setsession', $vars);
        $lastfmapi = new lastfmApi();
        
        $this->interfazBusqueda();
        
        echo "<div id='div_usertopartistas'>";
            $this->buscarArtistas();
        echo "</div>";
        $this->buscarTopArtistasGlobal();
        
        }    
            
        $token = JRequest::getCmd('token', null);
        
        if($token){
            $vars = array(
            'apiKey' => $this->api_key,
            'secret' => $this->secret,
            'token' => JRequest::getVar('token')
            );
	
            $auth = new lastfmApiAuth('getsession', $vars);
	
            setcookie('sessionkey', $auth->sessionKey);
            setcookie('username', $auth->username);
            setcookie('subscriber', $auth->subscriber);

            $lastfmapi_auth = new lastfmApiAuth('get', $vars);
            $lastfmapi = new lastfmApi();            
            }
        
        if ( !isset($lastfmapi_auth) ) {
		?><p>Para Acceder al servicio debe loguearse en Lastfm <a href="http://www.last.fm/api/auth/?api_key=<?php echo $this->api_key; ?>">use su cuenta</a>.</p>
	<?php }
    }

    public function interfazBusqueda() {
        ?>   <div align="center">
                <dd><input type="text" value="Usuario de LastFm" name="busqueda" id="busqueda" size="30"
                           onfocus="$('busqueda').value='';" />
                    <input type="button" class="button" 
                           onclick="
                               if($('busqueda').value!=''){
                                    ajaxpage('index.php?option=com_lastfm&task=lastfm.buscarArtistas&user=' + $('busqueda').value,'div_usertopartistas');
                               }else{
                                   alert('Por Favor Escriba Un nombre de Usuario');
                               }" value="GO!"
                           /></dd>
        </div>   
        <?php
        return TRUE;
    }
    
    public function cerrarSesion(){
        setcookie('sessionkey', null);
        setcookie('username', null);
        setcookie('subscriber', null);
        return true;        
    }

    public function buscarArtistas($user=null) {
        require_once 'lastfmapi/lastfmapi.php';
        $authVars = array(
            'apiKey' => $this->api_key,
            'secret' => $this->secret,
            'username' => $_COOKIE['username'],
            'sessionKey' => $_COOKIE['sessionkey'],
            'subscriber' => $_COOKIE['subscriber']
        );
        
        $config = array(
            'enabled' => true,
            'path' => DS.'lastfmapi'.DS,
            'cache_length' => 1800
        );
        
        $auth = new lastfmApiAuth('setsession', $authVars);
        
        $apiClass = new lastfmApi();
        $userClass = $apiClass->getPackage($auth, 'user', $config);
        
        if($user) $final_user = $user; else $final_user=$_COOKIE['username'];
        
        $methodVars = array(
            'user' => $final_user, 
            'api_key ' => $this->api_key,
            'limit' => 10,
            'page ' => 1
        );

        if ( $artists = $userClass->getTopArtists($methodVars) ) {
            echo '<b>Top 10 Artistas Recientes de la Cuenta</b></br>';
                foreach($artists as $artist => $a):
                        ?>
                <pre>
                        <table border="0" with="20%">
                            <tr>
                                <td rowspan="5" ><a target="_blank" href="<?php echo $a['url']; ?>"><img onload="ajaxpage('index.php?option=com_lastfm&task=lastfm.obtenerNoticiasFeeds&id=<?php echo $a['name'];?>','div_<?php echo $a['mbid'];?>');" src="<?php echo $a['images']['large']; ?>" /></a></td>
                            </tr>
                            <tr><td><a target="_blank" href="<?php echo $a['url']; ?>"><?php echo $a['name'];?></a></td></tr>
                            <tr><td>Veces Escuchado: <?php echo $a['playcount'];?></td></tr>
                            <tr><td>Seguidores: <?php echo $a['listeners'];?></td></tr>
                            <tr>
                                <td>  </td>
                            </tr>
                        </table>
                            <div id="div_<?php echo $a['mbid'];?>"></div>
                 </pre>       
                        <?php
                endforeach;
        }else{
            echo "No hay Artistas en el Top 10 de la cuenta";
            echo '<b>Error '.$userClass->error['code'].' - </b><i>'.$userClass->error['desc'].'</i></br>';
        }
        
    }
    
    public function buscarEventos() {
        require_once 'lastfmapi/lastfmapi.php';
        $authVars = array(
            'apiKey' => $this->api_key,
            'secret' => $this->secret,
            'username' => $_COOKIE['username'],
            'sessionKey' => $_COOKIE['sessionkey'],
            'subscriber' => $_COOKIE['subscriber']
        );
        
        $config = array(
            'enabled' => true,
            'path' => DS.'lastfmapi'.DS,
            'cache_length' => 1800
        );
        
        $auth = new lastfmApiAuth('setsession', $authVars);
        
        $apiClass = new lastfmApi();
        $userClass = $apiClass->getPackage($auth, 'user', $config);
        $methodVars = array(
            'user' => $_COOKIE['username'],
            'api_key ' => $this->api_key,
            'limit' => 1
        );
        
        if ( $events = $userClass->getEvents($methodVars) ) {
            echo '<b>Proximo Evento</b></br>';
            foreach($events as $k => $v){
                ?>
                <a target='_blank' href="<?php echo $v['url']; ?>" ><?php echo $v['title']; ?></a></br>
                Fecha: <?php echo gmdate("m/d/Y g:i:s A", $v['startDate']); ?></br>
                Organizador: <?php echo $v['headliner']; ?> </br>
                Descripcion : <?php echo $v['description']; ?> </br>
                <?php
            }
        }else{
            echo "No hay Actividades ";
            echo '<b>Error '.$userClass->error['code'].' - </b><i>'.$userClass->error['desc'].'</i></br>';
        }
    }
    
    public function buscarTopArtistasGlobal() {
        require_once 'lastfmapi/lastfmapi.php';
        $authVars = array(
            'apiKey' => $this->api_key,
            'secret' => $this->secret,
            'username' => $_COOKIE['username'],
            'sessionKey' => $_COOKIE['sessionkey'],
            'subscriber' => $_COOKIE['subscriber']
        );
        
        $config = array(
            'enabled' => true,
            'path' => DS.'lastfmapi'.DS,
            'cache_length' => 1800
        );
        
        $auth = new lastfmApiAuth('setsession', $authVars);
        
        $apiClass = new lastfmApi();
        $userClass = $apiClass->getPackage($auth, 'user', $config);
        $methodVars = array(
            'user' => $_COOKIE['username'],
            'api_key' => $this->api_key,
            'limit' => 10
        );
                
        if ( $artists = $userClass->getTopArtistsGlobal($methodVars) ) {
            echo '<b>Top Artistas Globales</b></br>';
                foreach($artists as $artist => $a):
                        ?>
                <pre>
                        <table border="0" with="20%">
                            <tr>
                                <td rowspan="5" ><a target="_blank" href="<?php echo $a['url']; ?>"><img onload="ajaxpage('index.php?option=com_lastfm&task=lastfm.ObtenerNoticiasFeeds&id=<?php echo $a['name'];?>','div_<?php echo $a['mbid'];?>');" src="<?php echo $a['images']['large']; ?>" /></a></td>
                            </tr>
                            <tr><td><a target="_blank" href="<?php echo $a['url']; ?>"><?php echo $a['name'];?></a></td></tr>
                            <tr><td>Veces Escuchado: <?php echo $a['playcount'];?></td></tr>
                            <tr><td>Seguidores: <?php echo $a['listeners'];?></td></tr>
                            <tr>
                                <td>  </td>
                            </tr>
                        </table>
                            <div id="div_<?php echo $a['mbid'];?>"></div>
                 </pre>       
                        <?php
                endforeach;
        }else{
            echo "No hay Artistas en el Top 10 ";
            echo '<b>Error '.$userClass->error['code'].' - </b><i>'.$userClass->error['desc'].'</i></br>';
        }
        
    }

    public function obtenerNoticiasFeeds($id){
        $url="http://news.google.com/news?ned=es&output=rss&num=1&q='$id'";
        $url = str_replace(' ', '%20', $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FAILONERROR,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $data = curl_exec($ch);                      
        curl_close($ch);
        
        $xml = new SimpleXmlElement($data, LIBXML_NOCDATA);
        
        //echo "<strong>".$xml->channel->title."</strong>";
        $cnt = count($xml->channel->item);
        for($i=0; $i<$cnt; $i++)
        {
            $url 	= $xml->channel->item[$i]->link;
            $title 	= $xml->channel->item[$i]->title;
            $desc = $xml->channel->item[$i]->description;
            ?>
                <dt><a href="<?php echo $url;?>"><?php echo $title;?></a></dt>
                <article><?php echo $desc;?></article> 
            <?php    
        }

    }
    
    public function getData(){
		$this->data=null;
		
		if(JRequest::getVar('idclase')!=null){

			$query = $this->_db->getQuery(true);
			$query->select('*');
			$query->from($this->_db->quoteName('#__clases'));
			$query->where($this->_db->quoteName('idclase') . ' = ' . (int) JRequest::getVar('idclase'));
			$this->_db->setQuery($query);
			$this->data = (array) $this->_db->loadAssoc();			
		}

		
		if ($this->data === null) {

			$this->data	= new stdClass();
			$app	= JFactory::getApplication();
			$params	= JComponentHelper::getParams('com_lastfm');

			// Override the base user data with any data in the session.
			$temp = (array)$app->getUserState('com_lastfm.clases.data', array());
			foreach ($temp as $k => $v) {
				$this->data->$k = $v;
			}

			// Get the dispatcher and load the users plugins.
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('user');

			// Trigger the data preparation event.
			$results = $dispatcher->trigger('onContentPrepareData', array('com_lastfm.clases', $this->data));

			// Check for errors encountered while preparing the data.
			if (count($results) && in_array(false, $results, true)) {
				$this->setError($dispatcher->getError());
				$this->data = false;
			}
		}
		
		if(isset($this->data)) return $this->data;
	}

    public function getForm($data = array(), $loadData = true){
		// Get the form.
		$form = $this->loadForm('com_lastfm.clases', 'clases', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

    protected function loadFormData() {
		return $this->getData();
	}
	
    protected function populateState(){
		// Get the application object.
		$app	= JFactory::getApplication();
		$params	= $app->getParams('com_lastfm');

		// Load the parameters.
		$this->setState('params', $params);
	}


}
