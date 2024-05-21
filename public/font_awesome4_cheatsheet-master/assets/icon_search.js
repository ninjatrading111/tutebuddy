$(document).ready(function(){
  var currentIconWrapper,
      selectedIndex = 0,
      iconCSS = 'div.row div i.fa',
      classNames = $('div.row span.classname'),
      iconWrapperCSS = 'div.row div.icon_wrapper',
      iconWrappers = $(iconWrapperCSS),
      scrollTopOffset = 100,
      scrollSpeed = 300,
      hoverable = true;

  function selectVisibleIcon(index){
    clearSelectedIcon();
    currentIconWrapper = visibleIconWrappers().eq(index);
    $('input.classname').val($('span.classname', currentIconWrapper).text());
    currentIconWrapper.addClass('found');
    scrollTo(currentIconWrapper);
  }

  function clearSelectedIcon(){
    if (currentIconWrapper){
      currentIconWrapper.removeClass('found');
    }
  }

  function visibleIconWrappers(){
    return $(iconWrapperCSS + ':visible');
  }

  function reset(){
    clearSelectedIcon();
    iconWrappers.removeClass('hidden');
    scrollTo(iconWrappers.first());
    $('#search_bar .classname').val('');
  }

  function disableHover(){
    hoverable = false;
    iconWrappers.removeClass('hoverable');
  }

  function enableHover(){
    hoverable = true;
    iconWrappers.addClass('hoverable');
  }

  function scrollTo(icon){
    // need to disable hover so that when the window is scrolling to the found icon, the mouseover events don't trigger the hover state
    disableHover();
    $(window).scrollTo(icon.offset().top - scrollTopOffset,
                       scrollSpeed,
                       {
                        'axis':'y',
                        onAfter:function(){
                          // after finishing scrolling, allow hover, BUT don't set the hoverable class so that the mouse over another icon
                          // doesn't trigger the hover state
                          hoverable = true;
                        }
                       });
  }

  function matchIconName(text, searchReverse){
    clearSelectedIcon();
    if (text.trim() == ''){
      return
    }

    classNames.each(function(){
      var className = $(this).text(),
          wrapper = $(this).parents('.icon_wrapper');
      if (className.match(new RegExp(text))){
        wrapper.removeClass('hidden');
      }
      else {
        wrapper.addClass('hidden');
      }
    })
  }

  function findFirstMatching(text){
    matchIconName(text);
    selectedIndex = 0;   // start with the first one
    selectVisibleIcon(selectedIndex);
  }

  function findNextMatching(text){
    selectedIndex += 1;
    if (selectedIndex > visibleIconWrappers().length - 1){
      selectedIndex = 0;
    }
    selectVisibleIcon(selectedIndex);
  }

  function findPrevMatching(text){
    selectedIndex -= 1;
    if (selectedIndex < 0){
      selectedIndex = visibleIconWrappers().length - 1;
    }
    selectVisibleIcon(selectedIndex);
  }

  // icon events
  iconWrappers.click(function(){
    $('#search_bar input.classname').val('fa ' + $('span.classname', this).html());
  }).mouseover(function(){
    // clear the currently selected icon when hovered
    clearSelectedIcon();
  }).mousemove(function(){
    // reenable hover if mouse moves over an icon, if in hoverable mode
    if (hoverable){
      enableHover();
    }
  })

  // select the entire text when an input receives focus
  $('#search_bar input').focus(function(){
    $(this).select();
  })

  // color field events - set color on keyup
  $('#search_bar input.color').keyup(function(){
    var color = $(this).val();
    if (!color.match(/#/)){
      color = '#' + color;
    }
    $('#icon_color_style_tag').html(iconCSS + '{ color: ' + color + '}');
  })

  // search field events
  $('#search_bar input.search').focus(function(){
    $(this).select();

  }).keyup(function(event){
    if ((event.keyCode >= 65 && event.keyCode <= 90) || event.keyCode == 8){  // a-z, backspace
      if ($(this).val().trim() == '') {
        reset();
      }
      else if ($(this).val().length > 1) {
        findFirstMatching($(this).val());
      }
    }
  }).keydown(function(event){
    if (event.keyCode == 13) { // enter
      if ($(this).val().length > 1) {
        if (event.shiftKey){  // go backwards
          findPrevMatching($(this).val());
        }
        else {  // go forwards
          findNextMatching($(this).val());
        }
      }
    }
  })

  // classname field events
  $('#search_bar input.classname').mouseup(function(event){
    // need this to prevent losing selected state when mouse clicked in chrome
    event.preventDefault();
  })

  // global esc key to clear search field
  $(document).keyup(function(event){
    if (event.keyCode == 27) {   // escape
      $('#search_bar input.search').val('');
      reset();
    }
  })

  // set initial focus in the search bar to start typing right away
  $('.search_bar input.search').focus();

})
