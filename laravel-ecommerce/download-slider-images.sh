#!/bin/bash

# Script to download sample slider images for e-commerce website
# Run this script to get beautiful slider images

echo "Downloading sample slider images..."

# Create images directory
mkdir -p public/images/sliders

# Download high-quality e-commerce slider images from Unsplash
echo "Downloading luxury perfume slider image..."
curl -L "https://images.unsplash.com/photo-1541643600914-78b084683601?w=1920&h=500&fit=crop&crop=center" -o public/images/sliders/slider-1-perfume.jpg

echo "Downloading fashion accessories slider image..."
curl -L "https://images.unsplash.com/photo-1445205170230-053b83016050?w=1920&h=500&fit=crop&crop=center" -o public/images/sliders/slider-2-fashion.jpg

echo "Downloading luxury jewelry slider image..."
curl -L "https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=1920&h=500&fit=crop&crop=center" -o public/images/sliders/slider-3-jewelry.jpg

echo "Downloading cosmetics slider image..."
curl -L "https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=1920&h=500&fit=crop&crop=center" -o public/images/sliders/slider-4-cosmetics.jpg

echo "Downloading luxury shopping slider image..."
curl -L "https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&h=500&fit=crop&crop=center" -o public/images/sliders/slider-5-shopping.jpg

echo "All slider images downloaded successfully!"
echo "Images saved to: public/images/sliders/"

# Make images web-optimized (if imagemagick is available)
if command -v convert &> /dev/null; then
    echo "Optimizing images for web..."
    for img in public/images/sliders/*.jpg; do
        convert "$img" -quality 80 -strip "$img"
    done
    echo "Images optimized!"
fi

echo "Done! You can now use these images in your slider."