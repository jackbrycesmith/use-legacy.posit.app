export const getRecorderMimeType = () => {
  if (!window.MediaRecorder) {
    return false;
  }

  return [
    "video/webm; codecs=vp9",
    "video/webm; codecs=vp8",
    "video/webm; codecs=h264",
    "video/mp4; codecs=h264",
    "video/webm",
    "video/mp4"
  ].find(type => MediaRecorder.isTypeSupported(type))
}
