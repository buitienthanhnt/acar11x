import { Link } from "@inertiajs/react";
import React from "react";

const BreadCategory = ({ categories }) => {
	return (
		<div className="bg-white p-1 rounded-md">
			<nav aria-label="breadcrumb" className="w-max">
				<ol className="flex w-full flex-wrap items-center rounded-md bg-slate-50 px-4 py-2">
					{categories.map((item, index) => (
						<Link href={route('list', { cat: item.id })} key={`cat-${index}`} >
							<li className="flex cursor-pointer items-center text-sm text-slate-500 transition-colors duration-300 hover:text-slate-800">
								<span className="font-semibold text-xl hover:text-purple-600">{item.name}</span>
								<span className="pointer-events-none mx-2 font-medium text-xl text-slate-800 ">
									/
								</span>
							</li>
						</Link>
					))}
				</ol>
			</nav>
		</div>
	)
}

export default BreadCategory;