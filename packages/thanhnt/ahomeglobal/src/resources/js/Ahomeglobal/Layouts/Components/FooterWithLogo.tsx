import { Link } from "@inertiajs/react";
import { Typography } from "@material-tailwind/react";

export function FooterWithLogo() {
	return (
		<footer className="w-full bg-gradient-to-r from-blue-gray-900 to-blue-gray-800 p-8 rounded-t-md">
			<div className="flex flex-row flex-wrap items-center justify-center gap-y-6 gap-x-12 text-center md:justify-between">
				{/* <img src="https://docs.material-tailwind.com/img/logo-ct-dark.png" alt="logo-ct" className="w-10" /> */}
				<ul className="flex flex-wrap items-center gap-y-2 gap-x-8">
					<li>
						{/* @ts-ignore */}
						<Link
							as="a"
							href="/contact"
							color="white"
							className="font-normal transition-colors text-white hover:text-blue-500 focus:text-blue-500"
						>
							Liên hệ
						</Link>
					</li>
					<li>
						{/* @ts-ignore */}
						<Link
							as="a"
							href="/about"
							color="white"
							className="font-normal transition-colors text-white hover:text-blue-500 focus:text-blue-500"
						>
							Giới thiệu
						</Link>
					</li>
				</ul>
			</div>
			<hr className="my-8 border-blue-gray-50" />
			{/* @ts-ignore */}
			<Typography color="blue-gray" className="text-center font-normal text-white">
				&copy; Ahome Global
			</Typography>
		</footer>
	);
}