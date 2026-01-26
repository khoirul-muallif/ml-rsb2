// E:\laragon\www\ml-rsb2\public\js\partials\audio.js

/**
 * Convert number to Indonesian audio sequence
 */
function convertToIndonesianAudio(number, playlist, baseUrl) {
    var num = parseInt(number);
    
    if(num == 0) {
        playlist.push(baseUrl + '/nol.wav');
        return;
    }
    
    // Ratusan
    if(num >= 200) {
        var ratusan = Math.floor(num / 100);
        playlist.push(baseUrl + '/' + ratusan + '.wav');
        playlist.push(baseUrl + '/ratus.wav');
        num = num % 100;
    } else if(num >= 100) {
        playlist.push(baseUrl + '/seratus.wav');
        num = num % 100;
    }
    
    // Puluhan
    if(num >= 20) {
        var puluhan = Math.floor(num / 10);
        playlist.push(baseUrl + '/' + puluhan + '.wav');
        playlist.push(baseUrl + '/puluh.wav');
        num = num % 10;
    } else if(num >= 11) {
        if(num == 11) {
            playlist.push(baseUrl + '/sebelas.wav');
        } else {
            var satuan = num - 10;
            playlist.push(baseUrl + '/' + satuan + '.wav');
            playlist.push(baseUrl + '/belas.wav');
        }
        return;
    } else if(num == 10) {
        playlist.push(baseUrl + '/sepuluh.wav');
        return;
    }
    
    // Satuan (1-9)
    if(num > 0) {
        playlist.push(baseUrl + '/' + num + '.wav');
    }
}

/**
 * Play audio sequence
 */
function playAudioSequence(playlist, index = 0) {
    if (index >= playlist.length) {
        console.log('✅ Audio playback complete');
        return;
    }
    
    console.log('🔊 Playing (' + (index + 1) + '/' + playlist.length + '):', playlist[index]);
    
    var audio = new Audio(playlist[index]);
    audio.volume = 1.0;
    audio.preload = 'auto';
    
    audio.play().catch(function(error) {
        console.error('❌ Error:', error);
        setTimeout(() => playAudioSequence(playlist, index + 1), 100);
    });
    
    audio.onended = () => setTimeout(() => playAudioSequence(playlist, index + 1), 150);
    audio.onerror = () => {
        console.error('❌ File not found:', playlist[index]);
        setTimeout(() => playAudioSequence(playlist, index + 1), 100);
    };
}

/**
 * Play antrian sequence with prefix
 * ✅ FIXED: Support multi-character prefix (split jadi per huruf)
 */
function playAntrianSequence(noantrian, loket, audioName = 'a') {
    var playlist = [];
    var baseUrl = '/plugins/anjungan/suara';
    
    // 1. Antrian
    playlist.push(baseUrl + '/antrian.wav');
    
    // 2. Prefix (split jika multi-character)
    // Contoh: 'bv' → ['b', 'v'], 'cs' → ['c', 's']
    var prefixChars = audioName.split('');
    for(var i = 0; i < prefixChars.length; i++) {
        playlist.push(baseUrl + '/' + prefixChars[i] + '.wav');
    }
    
    // 3. Nomor antrian
    convertToIndonesianAudio(noantrian, playlist, baseUrl);
    
    // 4. Counter
    if(loket) {
        playlist.push(baseUrl + '/counter.wav');
        // 5. Nomor loket
        convertToIndonesianAudio(loket, playlist, baseUrl);
    }
    
    console.log('🔊 Playlist:', playlist);
    playAudioSequence(playlist);
}