import { Typography } from "@material-tailwind/react";

export function FooterWithLogo() {
	return (
		<footer className="w-full bg-gradient-to-r from-blue-gray-900 to-blue-gray-800  p-8 rounded-t-md">
			<div className="flex flex-row flex-wrap items-center justify-center gap-y-6 gap-x-12 text-center md:justify-between">
				{/* <img src="https://docs.material-tailwind.com/img/logo-ct-dark.png" alt="logo-ct" className="w-10" /> */}
				<ul className="flex flex-wrap items-center gap-y-2 gap-x-8">
					<li>
						<Typography
							as="a"
							href="#"
							color="white"
							className="font-normal transition-colors hover:text-blue-500 focus:text-blue-500"
						>
							About Us
						</Typography>
					</li>
					<li>
						<Typography
							as="a"
							href="#"
							color="white"
							className="font-normal transition-colors hover:text-blue-500 focus:text-blue-500"
						>
							Contribute
						</Typography>
					</li>
					<li>
						<Typography
							as="a"
							href="#"
							color="white"
							className="font-normal transition-colors hover:text-blue-500 focus:text-blue-500"
						>
							Contact Us
						</Typography>
					</li>
				</ul>
			</div>
			<hr className="my-8 border-blue-gray-50" />
			<Typography color="blue-gray" className="text-center font-normal text-white">
				&copy; 2023 Material Tailwind
			</Typography>
		</footer>
	);
}