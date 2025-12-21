import { router } from '@inertiajs/react';
import { Button } from '@material-tailwind/react';

/**
 * Paypal component
 * 
 * Render a paypal checkout button
 * 
 * @return {JSX.Element} A paypal checkout button
 */

const Paypal = () => {

	const handlePaypalCheckout = () => {
		router.visit('/paypal/create');
	}

	return (
		<div className='flex justify-end w-full mt-2'>
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
		</div>
	)
}

export default Paypal;