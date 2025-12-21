import {
	Navbar,
	Typography,
	IconButton,
	Button,
	Input,
} from "@material-tailwind/react";
import { BellIcon, Cog6ToothIcon, UserCircleIcon, UserMinusIcon } from "@heroicons/react/24/solid";
import { Link } from "@inertiajs/react";
import usePageProps from "../../hooks/usePageProps";

export function NavbarDark() {
	const { auth: { user } } = usePageProps();

	return (
		<Navbar
			variant="gradient"
			color="blue-gray"
			className="mx-auto from-blue-gray-900 to-blue-gray-800 px-4 py-3 rounded-md"
		>
			<div className="flex flex-wrap items-center justify-between gap-y-4 text-white">
				<Link href={'/ahome/homes'}>
					<Typography
						as="a"
						variant="h6"
						className="mr-4 ml-2 cursor-pointer py-1.5"
					>
						Ahome Global
					</Typography>
				</Link>
				<div className="ml-auto flex gap-1 md:mr-4">
					{/* <IconButton variant="text" color="white"><Cog6ToothIcon className="h-6 w-6" /></IconButton> */}
					<Link href='/checkout'>
						<IconButton variant="text" color="white">
							<BellIcon className="h-6 w-6" />
						</IconButton></Link>
					{!user ?
						<Link href={'/ahome/login'}>
							<IconButton variant="text" color="white">
								<UserCircleIcon className="h-6 w-6" />
							</IconButton></Link>
						:
						<Link href={'/ahome/logout'}>
							<IconButton variant="text" color="white">
								<UserMinusIcon className="h-6 w-6" />
							</IconButton></Link>
					}
				</div>
				{/* <div className="relative flex w-full gap-2 md:w-max">
					<Input
						type="search"
						color="white"
						label="Type here..."
						className="pr-20"
						containerProps={{
							className: "min-w-[288px]",
						}}
					/>
					<Button
						size="sm"
						color="white"
						className="!absolute right-1 top-1 rounded"
					>
						Search
					</Button>
				</div> */}
			</div>
		</Navbar>
	);
}
