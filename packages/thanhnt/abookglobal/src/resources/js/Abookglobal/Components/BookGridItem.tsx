import { CurrencyDollarIcon, SparklesIcon } from "@heroicons/react/24/solid"
import { Link } from "@inertiajs/react"
import { sprintf } from "sprintf-js"
import Urls from "../network/Urls"
import { formatCurrency } from "@/Pages/Amuaglobal/Helper"

const BookGridItem = ({ book, selectedDates }: { book: any, selectedDates?: any }) => {

	return (
		<Link className="shadow-sm md:shadow-md hover:shadow-lg rounded-md gap-x-1 md:gap-x-4 flex md:flex-col 
			bg-gradient-to-r from-blue-gray-200 to-blue-gray-100 md:bg-transparent md:from-transparent md:to-transparent"
			queryStringArrayFormat={'brackets'}
			href={sprintf(Urls.bookDetail, [book.id])}
			data={{ selectedDates: selectedDates }}>
			<div >
				<img src={book.image_path} alt="avata hotel" className="max-w-40 md:max-w-60 object-contain lg:max-w-full h-full md:min-h-48 lg:min-h-60 rounded-md md:rounded-t-md md:rounded-b-none" />
			</div>
			<div className="flex flex-col justify-between gap-1 md:gap-2 p-1 md:py-2 h-full">
				<div>
					<h4 className="text-md lg:text-xl font-bold ">{book.name}</h4>
					<div className="flex gap-x-1 items-center">
						<SparklesIcon className="size-6 text-gray-800"></SparklesIcon>
						<p className="text-sm md:text-base lg:text-md font-semibold">{book.description}</p>
					</div>
				</div>
				<div className="flex items-center">
					<CurrencyDollarIcon className="size-5"></CurrencyDollarIcon>
					<p className="italic text-purple-600 font-semibold text-sm md:text-md">&nbsp;{formatCurrency(book.price)}</p>
				</div>
			</div>
		</Link>
	)
}

export default BookGridItem