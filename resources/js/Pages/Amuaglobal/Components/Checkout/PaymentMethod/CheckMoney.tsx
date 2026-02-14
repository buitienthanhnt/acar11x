import { useForm } from '@inertiajs/react';
import { Button } from '@material-tailwind/react';
import { useCallback } from 'react';
const CheckMoney = () => {

	const { post, data, processing } = useForm(
		{ paymentMethod: 'checkmoney', }
	);

	const handlePaypalCheckout = useCallback(() => {
		post(route('checkout.payment'));
	}, [post])

	return (
		<div>
			<h3>
				Chúng tôi sẽ liên hệ qua điện thoại xác nhận với quý khách hàng trong 60 phút sau khi xác nhận thanh toán
			</h3>
			<div className='flex flex-1 justify-end my-2'>
				{/* @ts-ignore */}
				<Button fullWidth onClick={handlePaypalCheckout} variant='outlined'>Thanh toán</Button>
			</div>
		</div>
	)
}

export default CheckMoney;