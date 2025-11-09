import { Carousel, Typography } from "@material-tailwind/react";

const CarouselImage = ({ content }) => {
	const jsonData = JSON.parse(content.value);

	return (
		<div className="py-1 flex justify-center items-center">
			<div className="w-full md:w-2/3 h-2/3 flex">
				<Carousel className="rounded-xl">
					{jsonData.map((img, index) => {
						return (
							<div className="relative" key={index.toString()}>
								<img
									src={img.imagePath}
									alt="image 1"
									className="h-1/2 w-full object-cover"
									style={{
										// maxHeight: 340
									}}
								>
								</img>
								<div className="absolute inset-0 grid h-full w-full items-end bg-black/25">
									<div className="w-3/4 pl-12 pb-12 md:w-2/4 md:pl-20 md:pb-20 lg:pl-32 lg:pb-32">
										<Typography
											variant="h1"
											color="white"
											className="mb-4 text-3xl md:text-4xl lg:text-5xl"
										>
											{img.title}
										</Typography>
									</div>
								</div>
							</div>

						)
					})}
				</Carousel>
			</div>
		</div>
	)
}

export default CarouselImage;