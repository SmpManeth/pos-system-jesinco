$(function() {

    // This "emoticon set" uses the Yahoo Instant Messenger smilies.. Feel free
    // to use this one or create your own!

    var smilies = {
        ':)': ['emoticon-0100-smile.gif', 'sadsmile'],
        ':(': ['emoticon-0101-sadsmile.gif', 'sadsmile'],
        ':D': ['emoticon-0102-bigsmile.gif', 'bigsmile'],
        ':8': ['emoticon-0103-cool.gif', 'cool'],
        ':O': ['emoticon-0104-surprised.gif', 'surprised'],
        ';)': ['emoticon-0105-wink.gif', 'wink'],
        ';(': ['emoticon-0106-crying.gif', 'wink'],
        '(:|': ['emoticon-0107-sweating.gif', 'sweating'],
        ':|': ['emoticon-0108-speechless.gif', 'speechless'],
        ':*': ['emoticon-0109-kiss.gif', 'kiss'],
        ':P': ['emoticon-0110-tongueout.gif', 'tongueout'],
        ':$': ['emoticon-0111-blush.gif', 'blush'],
        ':^)': ['emoticon-0112-wondering.gif', 'wondering'],
        '|-)': ['emoticon-0113-sleepy.gif', 'sleepy'],
        '|(': ['emoticon-0114-dull.gif', 'dull'],
        ':inlove:': ['emoticon-0115-inlove.gif', 'inlove'],
        '])': ['emoticon-0116-evilgrin.gif', 'evilgrin'],
        ':talk:': ['emoticon-0117-talking.gif', 'talking'],
        '|-()': ['emoticon-0118-yawn.gif', 'yawn'],
        ':puke:': ['emoticon-0119-puke.gif', 'puke'],
        ':doh:': ['emoticon-0120-doh.gif', 'doh'],
        ':@': ['emoticon-0121-angry.gif', 'angry'],
        ':M': ['emoticon-0122-itwasntme.gif', 'itwasntme'],
        ':S': ['emoticon-0124-worried.gif', 'worried'],
        ':mm:': ['emoticon-0125-mmm.gif', 'mmm'],
        'B-|': ['emoticon-0126-nerd.gif', 'nerd'],
        ':X': ['emoticon-0127-lipssealed.gif', 'lipssealed'],
        ':hi:': ['emoticon-0128-hi.gif', 'hi'],
        ':call:': ['emoticon-0129-call.gif', 'call'],
        ':devil:': ['emoticon-0130-devil.gif', 'devil'],
        ':angel:': ['emoticon-0131-angel.gif', 'angel'],
        ':envy:': ['emoticon-0132-envy.gif', 'envy'],
        ':wait:': ['emoticon-0133-wait.gif', 'wait'],
        ':makeup:': ['emoticon-0135-makeup.gif', 'makeup'],
        ':giggle:': ['emoticon-0136-giggle.gif', 'giggle'],
        ':clap:': ['emoticon-0137-clapping.gif', 'clapping'],
        ':?': ['emoticon-0138-thinking.gif', 'thinking'],
        ':whew:': ['emoticon-0141-whew.gif', 'whew'],
        ':happy:': ['emoticon-0142-happy.gif', 'happy'],
        ':smirk:': ['emoticon-0143-smirk.gif', 'smirk'],
        ':nod:': ['emoticon-0144-nod.gif', 'nod'],
        ':shake:': ['emoticon-0145-shake.gif', 'shake'],
        '(Y)': ['emoticon-0148-yes.gif', '(Y)'],
        '<3': ['emoticon-0152-heart.gif', 'heart'],
        ':<': ['emoticon-0153-brokenheart.gif', 'brokenheart'],
        ':rain:': ['emoticon-0156-rain.gif', 'rain'],
        ':sun:': ['emoticon-0157-sun.gif', 'sun'],
        ':music:': ['emoticon-0159-music.gif', 'music'],
        ':movie:': ['emoticon-0160-movie.gif', 'movie'],
        ':ph:': ['emoticon-0161-phone.gif', 'phone'],
        ':coffee:': ['emoticon-0162-coffee.gif', 'coffee'],
        ':^': ['emoticon-0166-cake.gif', 'cake'],
        ':cake:': ['emoticon-0166-cake.gif', 'cake'],
        ':fu:': ['emoticon-0173-middlefinger.gif', 'mf'],
        ':headbang:': ['emoticon-0179-headbang.gif', 'headbang'],
        ':swear:': ['emoticon-0183-swear.gif', 'swear']
    };

    // Add the above smilies, setting the appropirate base_url.
    emotify.emoticons(site_url + 'images/smileys/', smilies);


    // Generate "emoticons key" table for this example.
//    var html = '',
//            cols = 7,
//            i = -1;
//
//    $.each(emotify.emoticons(), function(k, v) {
//        i++;
//        html += i % cols == 0 ? '<tr>' : '';
//        html += '<td class="key1">' + k + '<\/td><td class="key2">' + emotify(k) + '<\/td>';
//        html += i % cols == cols - 1 ? '<\/tr>' : '';
//    });
//
//    while (++i % cols) {
//        html += '<td class="key3" colspan="2"><\/td>';
//    }
//
//    $('#key').html('<table>' + html + '<\/table>');

    // When the textarea changes, update the output!
//    $('textarea').keyup(function() {
//        var text = $(this).val(),
//                html = emotify(text);
//        $('#output').html(html.replace(/\n/g, "<br/>"));
//
//    }).keyup();

});