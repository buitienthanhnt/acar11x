export const formatTimeNumberToString = (time: number, showTenths: boolean = false, showAll: boolean = false): string => {
  if (!showAll) {
    let result = '';
    const tenths = time % 10;
    const hourse = Math.floor(time / 36000);
    if (hourse > 0) {
      result += `${hourse > 9 ? hourse : `0${hourse}`}:`;
    }
    const minutes = Math.floor(time / 600);
    if (minutes > 0 || hourse > 0) {
      result += `${minutes > 9 ? minutes : `0${minutes}`}:`;
    }
    const seconds = Math.floor(time % 600 / 10);
    return result += `${seconds < 10 ? `0${seconds}` : seconds}${showTenths ? `:0${tenths}` : ''}`;
  }
  return `${Math.floor(time / 36000) > 9 ? Math.floor(time / 36000) : `0${Math.floor(time / 36000)}`}:${Math.floor((time % 36000) / 600) > 9 ? Math.floor((time % 36000) / 600) : `0${Math.floor((time % 36000) / 600)}`}:${Math.floor(time % 600 / 10) < 10 ? `0${Math.floor(time % 600 / 10)}` : Math.floor(time % 600 / 10)}${showTenths ? `:0${time % 10}` : ''}`;
}