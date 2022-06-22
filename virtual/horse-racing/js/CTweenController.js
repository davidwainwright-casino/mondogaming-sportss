
function CTweenController(){
    
    this.tweenValue = function( fStart, fEnd, fLerp ){
        return fStart + fLerp *( fEnd-fStart);     
    };
    
    this.easeLinear = function(t, b, c, d) {
            return c*t/d + b;
    };
    
    this.easeInCubic = function(t, b, c, d) {
	var tc=(t/=d)*t*t;
	return b+c*(tc);
    };


    this.easeBackInQuart =  function(t, b, c, d) {
	var ts=(t/=d)*t;
	var tc=ts*t;
	return b+c*(2*ts*ts + 2*tc + -3*ts);
    };
    
    this.easeInBack = function(t, b, c, d ) {
        return c*(t/=d)*t*((1.70158+1)*t - 1.70158) + b;
    };
    
    this.easeOutCubic = function(t, b, c, d){
        return c*((t=t/d-1)*t*t + 1) + b;
    };
    
    this.getTrajectoryPoint = function(t,p){
        var result = new createjs.Point();
        var oneMinusTSq = (1-t) * (1-t);
        
        var TSq = t*t;

        result.x = oneMinusTSq*p.start.x+2*(1-t)*t*p.traj.x+TSq*p.end.x;
        result.y = oneMinusTSq*p.start.y+2*(1-t)*t*p.traj.y+TSq*p.end.y;

        return result;
    };
}