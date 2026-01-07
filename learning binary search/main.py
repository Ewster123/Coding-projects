def binary_search(arr, target):
    """
    Perform a binary search to find the target element in the given sorted array.

    Args:
    - arr: A sorted list of elements to search through.
    - target: The element to search for.

    Returns:
    - index: The index of the target element if found, otherwise -1.
    """
    low = 0
    high = len(arr) - 1

    while low <= high:
        mid = (low + high) // 2
        if arr[mid] == target:
            return mid  # Return the index if the target is found
        elif arr[mid] < target:
            low = mid + 1
        else:
            high = mid - 1

    return -1  # Return -1 if the target is not found

# Example usage:
my_list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
target_element = 5
result = binary_search(my_list, target_element)
if result != -1:
    print(f"Element {target_element} found at index {result}.")
else:
    print(f"Element {target_element} not found in the list.")
