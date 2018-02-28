var magalterEvents =  Class.create({
    
    events: [],
    
    initialize: function() {
        this.arrEvents = {};
        this.eventPrefix = '';
    },

    attachEventHandler : function(eventName, handler) {
        if ((typeof handler == 'undefined') || (handler == null)) {
            return;
        }
        eventName = eventName + this.eventPrefix;       
        if (this.arrEvents[eventName] == null){
            this.arrEvents[eventName] = [];
        }       
        var asynchVar = arguments.length > 2 ? arguments[2] : false;
        var handlerObj = {
            method: handler,
            asynch: asynchVar
        };
        this.arrEvents[eventName].push(handlerObj);
    },
 
    removeEventHandler : function(eventName, handler) {
        eventName = eventName + this.eventPrefix;
        if (this.arrEvents[eventName] != null){
            this.arrEvents[eventName] = this.arrEvents[eventName].reject(function(obj) { return obj.method == handler; });
        }
    },
 
    clearEventHandlers : function(eventName) {
        eventName = eventName + this.eventPrefix;
        this.arrEvents[eventName] = null;
    },
 
    clearAllEventHandlers : function() {
        this.arrEvents = {};
    },
 
    fireEvent : function(eventName) {
        var evtName = eventName + this.eventPrefix;
        var results = [];
        var result;
        
        
        if (this.arrEvents[evtName] != null) {
            var len = this.arrEvents[evtName].length; //optimization
            for (var i = 0; i < len; i++) {
                try {
                    if (arguments.length > 1) {
                        if (this.arrEvents[evtName][i].asynch) {
                            var eventArgs = arguments[1];
                            var method = this.arrEvents[evtName][i].method.bind(this);
                            setTimeout(function() { method(eventArgs) }.bind(this), 10);
                        }
                        else{
                            result = this.arrEvents[evtName][i].method(arguments[1]);
                        }
                    }
                    else {
                      
                        if (this.arrEvents[evtName][i].asynch) {
                            var eventHandler = this.arrEvents[evtName][i].method;
                            setTimeout(eventHandler, 1);
                        }
                        else if (this.arrEvents && this.arrEvents[evtName] && this.arrEvents[evtName][i] && this.arrEvents[evtName][i].method){
                            result = this.arrEvents[evtName][i].method();
                        }
                    }
                    results.push(result);
                }
                catch (e) {                 
                }
            }
        }
        return results;
    }
});

var magalterAjaxcartEvents = new magalterEvents();




