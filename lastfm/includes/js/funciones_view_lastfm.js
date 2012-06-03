function funciones_clases(){
	
	function adicionar_clases(){
		SqueezeBox.setContent('iframe','index.php?option=com_eventos&tmpl=component&view=clases');
	}
    
    function eliminar_clases(){
    	cad='';
    	d=0;
    	checks=$$(document.getElementsByName('checks'));
    	
    	for(i=0; i<checks.length; i++){
    		if(checks[i].checked){
    			if(d!=0) cad+=","
    			cad+=checks[i].value;
    			d++
    		}
    	}
    	if(cad==''){ alert("Debe seleccionar una Clase a eliminar"); return true;} 
    	if(confirm('Desea en realidad Eliminar esta Clase?')){
    		ajaxpage('index.php?option=com_eventos&task=clases.eliminar&cad='+cad,'mensajes');
    	}
    }
    
    var cmu = [
           {
               header: "Seleccionar",
               dataIndex: 'sele',
               dataType:'String',
               width:30
            },
            {
              header: "CLASE",
              dataIndex: 'clase',
              dataType:'String',
              width:200
             },
             {
              header: "ESTADO",
              dataIndex: 'estado',
              dataType:'String',
              width:100
              },
              {
               header: "EDITAR",
               dataIndex: 'editar',
               dataType:'String',
               width:100
               }];	
    
    window.addEvent("domready", function(){
    	
	    datagrid = new omniGrid('gridclases', {
	        columnModel: cmu,
	        buttons : [
	          {name: 'Adicionar', bclass: 'add', onclick : adicionar_clases},
	          {separator: true},
	          {name: 'Eliminar', bclass: 'delete', onclick : eliminar_clases}
	        ],
	        perPageOptions: [10,20,50,100,200],
	        perPage: 10,
	        page: 1,
	        pagination: true,
	        serverSort: true,
	        showHeader: true,
	        alternaterows: true,
	        sortHeader: true,
	        resizeColumns: true,
	        multipleSelection: true,
	        url:"index.php?option=com_eventos&task=clases.datos_grid",
				
	        width:800,
	        height: 400
	    });
	        		
     });
     
}//fin 
 

function editar_clase(idclase){
	SqueezeBox.setContent('iframe','index.php?option=com_eventos&tmpl=component&view=clases&idclase='+idclase);
}

function refrescar_grid_clases(){
	datagrid.refresh();
}