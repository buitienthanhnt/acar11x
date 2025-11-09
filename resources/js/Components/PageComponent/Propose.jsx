import React from "react";
import usePageRandom from "@/hook/usePageRandom";
import { Link } from "@inertiajs/react";
import { Spinner } from "@material-tailwind/react";

const Propose = () => {
	const { pages, isLoading, isError } = usePageRandom();

	if (isLoading) {
		return (
			<div className="flex justify-center items-center bg-white rounded-sm p-2">
				<Spinner></Spinner>
			</div>
		)
	}

	return (
		<div className="bg-white p-1 rounded-sm md:rounded-md space-y-1">
			<p className="font-bold text-xl underline my-1">Nội dung đề xuất:</p>
			<ul className="list-disc list-inside">
				{pages.map((page, index) =>
					<Link href={route('detail', { alias: page.alias })} key={`random-${index}`}>
						<li className="text-lg ml-2 hover:underline hover:text-light-blue-600">
							{page.title}
						</li>
					</Link>
				)}
			</ul>
		</div>
	)
}

export default Propose;