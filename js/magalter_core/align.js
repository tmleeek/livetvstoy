magalterOffsets = Class.create();
magalterOffsets.prototype = {
    
    horizontal: 0,
    vertical: 0,
    maxHeight: 700,
    zIndex: 1000,
    element: {},
    relativeElement: {},
    alignment: 'top-right',
    
    initialize: function(config) {
        
        this.element = config.element;
        this.relativeEl = config.relative;        
        
    },

    align: function() {
        var offsets = this.relativeEl.viewportOffset();
        var dimensions = this.relativeEl.getDimensions();
        var formDimensions = this.element.getDimensions();        
        var scrollOffsets = document.viewport.getScrollOffsets();

        var  topOffset = parseInt(offsets[1]) + parseInt(dimensions.height) + parseInt(this.vertical) + parseInt(scrollOffsets.top);  
   
        if(this.alignment == 'top-right') {          
            var leftOffset = parseInt(offsets[0]) - parseInt(formDimensions.width) + parseInt(dimensions.width) + parseInt(this.horizontal) + parseInt(scrollOffsets.left);
        }
        else {
            var leftOffset = parseInt(offsets[0]) + parseInt(this.horizontal) + parseInt(scrollOffsets.left);           
        }
        
        this.element.setStyle({
            position:'absolute',
            zIndex: this.zIndex,
            top:topOffset+'px',
            left:leftOffset+'px'
        });   
    },
    
    alignCenter: function() {

        var dimensions = this.element.getDimensions();       
        if(dimensions.height > this.maxHeight) {            
            dimensions.height = this.maxHeight;
            $(this.element).setStyle({overflow: 'auto', height: this.maxHeight + 'px'});          
        }       
        $(this.element).setStyle({
            position: 'fixed', 
            zIndex: this.zIndex,
            left: parseInt(document.viewport.getWidth()/2) - parseInt(dimensions.width/2) + 'px',
            top: parseInt(document.viewport.getHeight()/2) - parseInt(dimensions.height/2) + 'px'
        });
     
    }
}