import { Typography } from "@material-tailwind/react";

const Loading = () => {
	return (
		<div className="max-w-full animate-pulse p-2 bg-white rounded-md">
			<Typography
				as="div"
				variant="h1"
				className="mb-3 h-20 w-full rounded-full bg-gray-500"
			>
				&nbsp;
			</Typography>
			<Typography
				as="div"
				variant="paragraph"
				className="mb-3 h-20 w-full rounded-full bg-blue-500"
			>
				&nbsp;
			</Typography>
			<Typography
				as="div"
				variant="paragraph"
				className="mb-3 h-20 w-full rounded-full bg-orange-500"
			>
				&nbsp;
			</Typography>
			<Typography
				as="div"
				variant="h1"
				className="mb-3 h-20 w-full rounded-full bg-green-500"
			>
				&nbsp;
			</Typography>
			<Typography
				as="div"
				variant="paragraph"
				className="mb-3 h-20 w-full rounded-full bg-purple-500"
			>
				&nbsp;
			</Typography>
			<Typography
				as="div"
				variant="h1"
				className="mb-3 h-20 w-full rounded-full bg-green-500"
			>
				&nbsp;
			</Typography>
			<Typography
				as="div"
				variant="paragraph"
				className="mb-3 h-20 w-full rounded-full bg-purple-500"
			>
				&nbsp;
			</Typography>
		</div>
	)
}

export default Loading;