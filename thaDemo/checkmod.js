function checkMod10(n) {
                var i, e = 0, o = !1, a = String(n).replace(/[^\d]/g, "");
				var luhnArr = [0, 2, 4, 6, 8, 1, 3, 5, 7, 9];
                if (0 == a.length)
                    return !1;
                for (var r = a.length - 1; r >= 0; --r)
                    i = parseInt(a.charAt(r), 10),
                    e += (o = !o) ? i : luhnArr[i];
				return e;
                return e % 10 == 0
            }


var code = '9704368621549229935';

console.log('====================================');
console.log(checkMod10(code));
console.log('====================================');