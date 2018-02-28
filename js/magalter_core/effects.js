magalterEffect = Class.create();
magalterEffect.prototype = {
    
    effect: 4,
    
    effectDuration: 0.3,
    
    interactive: 2,
    
    config: {},
    
    initialize: function(config, callbacks) { 
        
        this.config = config;
        
        this.callbacks = callbacks;
      
    },
    
    interact: function() {
        
        var me = this; 
        this.config.each(function(el) {            
            var trigger = el.trigger;             
            document.observe(me.interactiveEffect['e' + me.interactive], function(event) {
                var target = $(event.target);
                if( target ) {
                    if(target.id == trigger || target.hasClassName(trigger) || target.descendantOf($(el.element))) { 
                        if(!$(el.element).visible()) {
                            if(me[trigger + '-lock'] || me[trigger + '-prepareHide']) {
                                return;
                            }   
                            me.showWithEffect($(el.element),  function() {
                                me[trigger + '-lock'] = false; 
                                if(me.callbacks.after) {
                                    me.callbacks.after();
                                }
                            } , function() {
                                if(me.callbacks.before) {
                                    me.callbacks.before();
                                }
                                me[trigger + '-lock'] = true;
                            });
                        } 
                        else {
                            me[trigger + '-prepareHide'] = false;
                        }
                        clearTimeout(me[trigger + '-timeout']); 
                    }
                    else {   
                        if(me[trigger + '-lock'] || me[trigger + '-prepareHide']) {
                            return;
                        }   
                        if($(el.element).visible()) {
                            me[trigger + '-prepareHide'] = true;
                            me[trigger + '-timeout'] = setTimeout(function() {                             
                                me.hideWithEffect($(el.element), function() {
                                    me[trigger + '-lock'] = false;
                                    me[trigger + '-prepareHide'] = false;
                                } , function() {
                                    me[trigger + '-lock'] = true;
                                });
                              
                            }, 300
                            );
                        }                                         
                    }
                }                  
            });        
        });       
    },
    
    interactiveEffect: {
        e1: 'mouseover',
        e2: 'click'        
    },
    
    effectsBlock: {
        e1: {
            show: 'Appear', 
            hide: 'Fade'
        },
        e2: {
            show: 'SlideDown', 
            hide: 'SlideUp'
        },
        e3: {
            show: 'BlindDown', 
            hide: 'BlindUp'
        },
        e4: {
            show: 'Grow', 
            hide: 'Shrink'
        }
    },
   
    showWithEffect: function(element) { 
         
        Effect[this.effectsBlock['e' + this.effect].show](element, {
            duration:this.effectDuration, 
            beforeStart: arguments[2] || function() { }, 
            afterFinish: arguments[1] || function() { }            
        });        
    },
    
    hideWithEffect: function(element) {
        
        Effect[this.effectsBlock['e' + this.effect].hide](element, {
            duration:this.effectDuration, 
            beforeStart: arguments[2] || function() { }, 
            afterFinish: arguments[1] || function() { }            
        });  
    },
    
    hideAll: function() {
        
        var me = this; 
        this.config.each(function(el) {            
            var trigger = el.trigger; 
            
            if(!$(el.element) || !$(el.element).visible()) {
                return;
            }
            
            me.hideWithEffect($(el.element),  function() {
                me[trigger + '-lock'] = false; 
                if(me.callbacks.after) {
                    me.callbacks.after();
                }
            } , function() {
                if(me.callbacks.before) {
                    me.callbacks.before();
                }
                me[trigger + '-lock'] = true;
            });           
        });
     }
}
 