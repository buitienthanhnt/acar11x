import { useForm } from '@inertiajs/react';
import { Button, Spinner } from '@material-tailwind/react';
import AwaitProcess from './AwaitProcess';

/**
 * Paypal component
 * 
 * Render a paypal checkout button
 * 
 * @return {JSX.Element} A paypal checkout button
 */

const Paypal = () => {

	const { post, data, processing } = useForm(
		{ paymentMethod: 'paypal', }
	);

	const handlePaypalCheckout = () => {
		post(route('checkout.payment'));
	}

	return (
		<div className='flex justify-end w-full mt-2'>
			{/* @ts-ignore */}
			<Button
				fullWidth
				variant="outlined"
				className="h-12 border-blue-500 focus:ring-blue-100/50 hover:bg-blue-100/50"
				onClick={handlePaypalCheckout}
			>
				<img
					src="http://acar11x.dev/ahome/assets/paypalIcon.svg"
					className="mx-auto grid h-12 w-16 -translate-y-7 place-items-center"
					alt="paypal"
				/>
			</Button>
			<AwaitProcess
				open={processing}
				headerContent="Đang xử lý, vui lòng chờ phản hồi!"
				// @ts-ignore
				bodyContent={<Spinner className="h-12 w-12" />}
			>
			</AwaitProcess>
		</div>
	)
}

export default Paypal;