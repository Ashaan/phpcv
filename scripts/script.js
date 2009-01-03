var current='presentation';
var xplock = [2007,2006,2005];

/**
 * Prototype
 **/
Array.prototype.inArray = function (value)
{
  var i;
  for (i=0; i < this.length; i++) {
    if (this[i] === value) {
      return true;
    }
  }
  return false;
};
Array.prototype.addValue = function (value)
{
  if (!this.inArray(value)) {
    this[this.length] = value;
  }
};
Array.prototype.delValue = function (value)
{
  var i;
  var tmp = new Array();
  for (i=0; i<this.length; i++) {
    if (this[i] != value) {
      tmp[tmp.length] = this[i];
    }
  }
  return tmp;
}

/**
 * Menu
 **/
function show(what)
{
  if (what!=current) {
    document.getElementById(current).style.display  = 'none';
    document.getElementById(what).style.display     = 'block';
    document.getElementById('m'+what).className     = 'submenuh';
  }
}

function unshow(what)
{
  if (what!=current) {
    document.getElementById(what).style.display     = 'none';
    document.getElementById(current).style.display  = 'block';
    document.getElementById('m'+what).className     = 'submenu';
  }
}

function set(what)
{
  if (what!=current) {
    document.getElementById('m'+current).className     = 'submenu';
    current=what;
    document.getElementById('m'+current).className     = 'submenuh';
  }
}

/**
 * Experience
 **/
function xpshow(what)
{
  if (!xplock.inArray(what)) {
    document.getElementById('xp'+what).style.display     = 'block';
  }
}

function xpunshow(what)
{
  if (!xplock.inArray(what)) {
    document.getElementById('xp'+what).style.display     = 'none';
  }
}

function xpswitch(what)
{
  tmp = document.getElementById('xp'+what)

  if (xplock.inArray(what)) {
    xplock = xplock.delValue(what);
    tmp.style.display = 'none';
  } else {
    xplock.addValue(what);
    tmp.style.display = 'block';
  }
}


/**
 * Theme Switcher
 **/
function switchStyle(s)
{
  if (!document.getElementsByTagName) {
    return;
  }
  var el = document.getElementsByTagName("link");
  for (var i = 0; i < el.length; i++ ) {
    if (el[i].getAttribute("rel").indexOf("style") != -1 && el[i].getAttribute("title")) {
      el[i].disabled = true;
      if (el[i].getAttribute("title") == s) {
          el[i].disabled = false;
      }
    }
  }
}
