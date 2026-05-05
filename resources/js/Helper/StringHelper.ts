export function randomString(length) {
  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  let result = "";
  for (let i = 0; i < length; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return result;
}

export const formatPrice = (price: number, currency = 'vnđ'): string => {
  const giaTri = Math.abs(price);
  const ty = Math.floor(giaTri / 1000000);
  let sodu = giaTri - ty * 1000000;
  const trieu = Math.floor(sodu / 1000);
  sodu = sodu - trieu * 1000;
  const nghin = Math.round(sodu);
  return `${ty ? ty + ' tỷ ' : ''}${trieu ? trieu + ' triệu ' : ''}${nghin ? nghin + ' nghìn' : ''
    } ${currency}`;
};

