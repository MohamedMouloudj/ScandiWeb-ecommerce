import type { ProductImage } from "@/types/Product";
import { ChevronLeftIcon, ChevronRightIcon } from "lucide-react";
import { useState } from "react";
import GalleryThumbnails from "./GalleryThumbnails";

export default function GalleryCarousel({
  images,
  productName,
}: {
  images: ProductImage[];
  productName: string;
}) {
  const [mainIdx, setMainIdx] = useState(0);
  const mainImage = images[mainIdx];

  const handlePrev = () => {
    setMainIdx((prev) => (prev === 0 ? images.length - 1 : prev - 1));
  };
  const handleNext = () => {
    setMainIdx((prev) => (prev === images.length - 1 ? 0 : prev + 1));
  };
  const handleSelect = (idx: number) => setMainIdx(idx);
  return (
    <div className="flex gap-4" data-testid="product-gallery">
      <GalleryThumbnails
        images={images}
        selectedIdx={mainIdx}
        onSelect={handleSelect}
      />
      <div className="relative flex-center">
        <button className="carousel-button left-2" onClick={handlePrev}>
          <ChevronLeftIcon className="w-6 h-6" />
        </button>
        <img
          src={mainImage.imageUrl}
          alt={productName}
          className="w-sm h-[320px] sm:w-lg sm:h-[448px] object-contain rounded"
        />
        <button className="carousel-button right-2" onClick={handleNext}>
          <ChevronRightIcon className="w-6 h-6" />
        </button>
      </div>
    </div>
  );
}
