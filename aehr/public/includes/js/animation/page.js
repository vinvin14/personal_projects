// Get the Sequence element
var sequenceElement = document.getElementById("sequence");

// See: http://sequencejs.com/documentation/#options
var options = {
    animateCanvas: false,
    phaseThreshold: false,
    preloader: true,
    fadeStepWhenSkipped: true,
    reverseWhenNavigatingBackwards: true
}

var mySequence = sequence(sequenceElement, options);
