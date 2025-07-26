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
  const [isTransitioning, setIsTransitioning] = useState(false);

  const handlePrev = () => {
    if (isTransitioning) return;
    setIsTransitioning(true);
    setMainIdx((prev) => (prev === 0 ? images.length - 1 : prev - 1));
    setTimeout(() => setIsTransitioning(false), 300);
  };

  const handleNext = () => {
    if (isTransitioning) return;
    setIsTransitioning(true);
    setMainIdx((prev) => (prev === images.length - 1 ? 0 : prev + 1));
    setTimeout(() => setIsTransitioning(false), 300);
  };

  const handleSelect = (idx: number) => {
    if (isTransitioning || idx === mainIdx) return;
    setIsTransitioning(true);
    setMainIdx(idx);
    setTimeout(() => setIsTransitioning(false), 300);
  };

  return (
    <div
      className="flex gap-4 max-h-[320px] sm:max-h-[448px]"
      data-testid="product-gallery"
    >
      <GalleryThumbnails
        images={images}
        selectedIdx={mainIdx}
        onSelect={handleSelect}
      />
      <div className="relative flex-center overflow-hidden">
        <button
          className="carousel-button left-2 z-10"
          onClick={handlePrev}
          disabled={isTransitioning}
        >
          <ChevronLeftIcon className="w-6 h-6" />
        </button>

        <div className="relative w-sm h-[320px] sm:w-lg sm:h-[448px]">
          {images.map((image, idx) => (
            <img
              key={idx}
              src={image.imageUrl}
              alt={`${productName} - Image ${idx + 1}`}
              className={`absolute inset-0 w-full h-full object-contain rounded transition-transform duration-300 ease-in-out ${
                idx === mainIdx
                  ? "translate-x-0"
                  : idx < mainIdx
                  ? "-translate-x-full"
                  : "translate-x-full"
              }`}
            />
          ))}
        </div>

        <button
          className="carousel-button right-2 z-10"
          onClick={handleNext}
          disabled={isTransitioning}
        >
          <ChevronRightIcon className="w-6 h-6" />
        </button>
      </div>
    </div>
  );
}
