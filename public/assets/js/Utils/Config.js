const LIBRARY = (function() {
     var _args = {};
     return {
         set: (key, value) => {
             _args[key] = value;
         }, 
         get: (key) => _args[key]
     };
})();