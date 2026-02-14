export const formatCurrency = (value: number) => {
	// en-US || vi-VN	
	return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
}