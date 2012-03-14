<?php

function get_itunes_instance() {
  return new COM("iTunes.Application");
}

function dispatch() {
  $cmd = "";
  if (isset($_GET["cmd"])) {
    $cmd = $_GET["cmd"];
    $iTunes = get_itunes_instance();
  }
  $args = array();
  foreach($_GET as $key => $value) {
    $do = preg_match("/arg([0-9]*)/", $key, $matches);
    if ($do == true) {
      $args[$matches['1']] = $value;
    }
  }
  $result = "";
  if ($cmd == "") {
    return;
  } else if ($cmd == "getcurrenttrack") {
    $result = serialize_track(get_current_track($iTunes, $args));
  } else if ($cmd == "getobject") {
    $result = get_object($iTunes, $args);
  } else if ($cmd == "getplayerposition") {
    $result = get_player_position($iTunes, $args);
  } else if ($cmd == "getplayerstate") {
    $result = get_player_state($iTunes, $args);
  } else if ($cmd == "getplaylist") {
    $result = serialize_playlist(get_playlist($iTunes, $args));
  } else if ($cmd == "getplaylists") {
    $result = serialize_playlist_collection(get_playlists($iTunes, $args));
  } else if ($cmd == "gettrack") {
    $result = serialize_track(get_track($iTunes, $args));
  } else if ($cmd == "gettracksinplaylist") {
    $result = serialize_track_collection(get_tracks_in_playlist($iTunes, $args));
  } else if ($cmd == "getvolume") {
    $result = get_volume($iTunes, $args);
  } else if ($cmd == "ismute") {
    $result = is_mute($iTunes, $args);
  } else if ($cmd == "nexttrack") {
    $result = next_track($iTunes, $args);
  } else if ($cmd == "pause") {
    $result = pause($iTunes, $args);
  } else if ($cmd == "play") {
    $result = play($iTunes, $args);
  } else if ($cmd == "playpause") {
    $result = play_pause($iTunes, $args);
  } else if ($cmd == "previoustrack") {
    $result = previous_track($iTunes, $args);
  } else if ($cmd == "search") {
    $result = serialize_track_collection(search($iTunes, $args));
  } else if ($cmd == "setplayerposition") {
    $result = set_player_position($iTunes, $args);
  } else if ($cmd == "setvolume") {
    $result = set_volume($iTunes, $args);
  } else if ($cmd == "stop") {
    $result = stop($iTunes, $args);
  } else if ($cmd == "setmute") {
    $result = set_mute($iTunes, $args);
  }
  $result = json_encode($result);
  if ($result != "null") {
    echo $result;
  }
}

function serialize_track($track) {
  $result = array();
  $result["album"] = $track->Album;
  $result["artist"] = $track->Artist;
  $result["dateadded"] = $track->DateAdded;
  $result["duration"] = $track->Duration;
  $result["modificationdate"] = $track->ModificationDate;
  $result["name"] = $track->Name;
  $result["playedcount"] = $track->PlayedCount;
  $result["playeddate"] = $track->PlayedDate;
  $result["playlistid"] = $track->PlaylistID;
  $result["rating"] = $track->Rating;
  $result["sourceid"] = $track->SourceID;
  $result["trackdatabaseid"] = $track->TrackDatabaseID;
  $result["tracknumber"] = $track->TrackNumber;
  $result["trackid"] = $track->TrackID;
  $result["year"] = $track->Year;
  return $result;
}

function serialize_track_collection($track_collection) {
  $result = array();
  if (!is_null($track_collection) && ($track_collection->Count > 0)) {
    for ($i = 1; $i <= $track_collection->Count; $i++) {
      $result[$i-1] = serialize_track($track_collection->Item($i));
    }
  }
  return $result;
}

function serialize_playlist($playlist) {
  $result = array();
  $result["duration"] = $playlist->Duration;
  $result["kind"] = $playlist->Kind;
  $result["name"] = $playlist->Name;
  $result["playlistid"] = $playlist->PlaylistID;
  $result["shuffle"] = $playlist->Shuffle;
  $result["size"] = $playlist->Size;
  $result["songrepeat"] = $playlist->SongRepeat;
  $result["sourceid"] = $playlist->SourceID;
  return $result;
}

function serialize_playlist_collection($playlist_collection) {
  $result = array();
  if (!is_null($playlist_collection) && ($playlist_collection->Count > 0)) {
    for ($i = 1; $i <= $playlist_collection->Count; $i++) {
      $result[$i-1] = serialize_playlist($playlist_collection->Item($i));
    }
  }
  return $result;
}

function get_current_track($iTunes, $args) {
  return $iTunes->CurrentTrack;
}

function get_object($iTunes, $args) {
  $count = count($args);
  if ($count < 4) {
    $args[3] = 0;
  }
  if ($count < 3) {
    $args[2] = 0;
  }
  if ($count < 2) {
    $args[1] = 0;
  }
  if ($count < 1) {
    $args[0] = 0;
  }
  return $iTunes->GetITObjectByID($args[0], $args[1], $args[2], $args[3]);
}

function get_player_position($iTunes, $args) {
  return $iTunes->PlayerPosition;
}

function get_player_state($iTunes, $args) {
  $state = $iTunes->PlayerState;
  return $state;
}

function get_playlist($iTunes, $args) {
  return get_object($iTunes, $args);
}

function get_playlists($iTunes, $args) {
  return $iTunes->LibrarySource->Playlists;
}

function get_track($iTunes, $args) {
  return get_object($iTunes, $args);
}

function get_tracks_in_playlist($iTunes, $args) {
  return get_object($iTunes, $args)->Tracks;
}

function get_volume($iTunes, $args) {
  return $iTunes->SoundVolume;
}

function is_mute($iTunes, $args) {
  return $iTunes->Mute;
}

function next_track($iTunes, $args) {
  $iTunes->NextTrack();
}

function pause($iTunes, $args) {
  $iTunes->Pause();
}

function play($iTunes, $args) {
  $iTunes->Play();
}

function play_pause($iTunes, $args) {
  $iTunes->PlayPause();
}

function previous_track($iTunes, $args) {
  $iTunes->PreviousTrack();
}

function search($iTunes, $arg) {
  return $iTunes->LibraryPlaylist->Search($arg[0], $arg[1]);
}

function set_mute($iTunes, $args) {
  $iTunes->Mute = $args[0];
}

function set_player_position($iTunes, $args) {
  $iTunes->PlayerPosition = $args[0];
}

function set_volume($iTunes, $args) {
  $iTunes->SoundVolume = $args[0];
}

function stop($iTunes, $args) {
  $iTunes->Stop();
}

dispatch();

?>